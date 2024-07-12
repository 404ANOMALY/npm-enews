import tkinter as tk
from tkinter import messagebox, simpledialog, ttk
from ClientApplication.authenticator import Authenticator
from ClientApplication.user_manager import UserManager

class LoginWindow(tk.Tk):
    def __init__(self, wsdl_url):
        super().__init__()
        self.wsdl_url = wsdl_url
        self.authenticator = Authenticator(wsdl_url)
        self.title("Login")
        self.geometry("400x300")

        self.create_widgets()

    def create_widgets(self):
        tk.Label(self, text="Nom d'utilisateur").pack(pady=5)
        self.username_entry = tk.Entry(self)
        self.username_entry.pack(pady=5)

        tk.Label(self, text="Mot de passe").pack(pady=5)
        self.password_entry = tk.Entry(self, show="*")
        self.password_entry.pack(pady=5)

        tk.Button(self, text="Login", command=self.authenticate).pack(pady=20)

    def authenticate(self):
        username = self.username_entry.get()
        password = self.password_entry.get()
        user = self.authenticator.authenticate(username, password)

        if user and user.role == 'admin':
            messagebox.showinfo("Succès", "Authentification réussie.")
            self.destroy()
            token = 'valid-token'  # Vous devriez récupérer un token valide après l'authentification
            user_manager = UserManager(self.wsdl_url, token)
            UserManagementWindow(user_manager).mainloop()
        else:
            messagebox.showerror("Erreur", "Nom d'utilisateur ou mot de passe incorrect, ou vous n'êtes pas administrateur.")


class UserManagementWindow(tk.Tk):
    def __init__(self, user_manager):
        super().__init__()
        self.user_manager = user_manager
        self.title("Gestion des utilisateurs")
        self.geometry("800x600")

        self.create_widgets()

    def create_widgets(self):
        self.user_list = ttk.Treeview(self, columns=("ID", "Username", "Email", "Role"), show='headings')
        self.user_list.heading("ID", text="ID")
        self.user_list.heading("Username", text="Nom d'utilisateur")
        self.user_list.heading("Email", text="Email")
        self.user_list.heading("Role", text="Rôle")
        self.user_list.pack(fill=tk.BOTH, expand=True)

        self.refresh_button = tk.Button(self, text="Rafraîchir", command=self.refresh_users)
        self.refresh_button.pack(side=tk.LEFT, padx=10, pady=10)

        self.add_button = tk.Button(self, text="Ajouter", command=self.add_user)
        self.add_button.pack(side=tk.LEFT, padx=10, pady=10)

        self.update_button = tk.Button(self, text="Modifier", command=self.update_user)
        self.update_button.pack(side=tk.LEFT, padx=10, pady=10)

        self.remove_button = tk.Button(self, text="Supprimer", command=self.remove_user)
        self.remove_button.pack(side=tk.LEFT, padx=10, pady=10)

        self.refresh_users()

    def refresh_users(self):
        for row in self.user_list.get_children():
            self.user_list.delete(row)

        users = self.user_manager.list_users()
        if users:
            for user in users:
                self.user_list.insert("", "end", values=(user['id'], user['username'], user['email'], user['role']))
        else:
            messagebox.showerror("Erreur", "Erreur lors de la récupération des utilisateurs.")

    def add_user(self):
        username = simpledialog.askstring("Nom d'utilisateur", "Entrez le nom d'utilisateur :")
        password = simpledialog.askstring("Mot de passe", "Entrez le mot de passe :")
        email = simpledialog.askstring("Email", "Entrez l'email :")
        role = simpledialog.askstring("Rôle", "Entrez le rôle (visitor, editor, admin) :")

        if username and password and email and role:
            user = {
                'id': '0',
                'username': username,
                'password': password,
                'email': email,
                'role': role
            }
            if self.user_manager.add_user(user):
                messagebox.showinfo("Succès", "Utilisateur ajouté avec succès.")
                self.refresh_users()
            else:
                messagebox.showerror("Erreur", "Erreur lors de l'ajout de l'utilisateur.")
        else:
            messagebox.showwarning("Attention", "Tous les champs sont requis.")

    def update_user(self):
        selected_item = self.user_list.selection()
        if not selected_item:
            messagebox.showwarning("Attention", "Veuillez sélectionner un utilisateur à modifier.")
            return

        user_id = self.user_list.item(selected_item, "values")[0]
        username = simpledialog.askstring("Nom d'utilisateur", "Entrez le nouveau nom d'utilisateur :")
        password = simpledialog.askstring("Mot de passe", "Entrez le nouveau mot de passe :")
        email = simpledialog.askstring("Email", "Entrez le nouvel email :")
        role = simpledialog.askstring("Rôle", "Entrez le nouveau rôle (visitor, editor, admin) :")

        if username and password and email and role:
            user = {
                'id': user_id,
                'username': username,
                'password': password,
                'email': email,
                'role': role
            }
            if self.user_manager.update_user(user):
                messagebox.showinfo("Succès", "Utilisateur modifié avec succès.")
                self.refresh_users()
            else:
                messagebox.showerror("Erreur", "Erreur lors de la modification de l'utilisateur.")
        else:
            messagebox.showwarning("Attention", "Tous les champs sont requis.")

    def remove_user(self):
        selected_item = self.user_list.selection()
        if not selected_item:
            messagebox.showwarning("Attention", "Veuillez sélectionner un utilisateur à supprimer.")
            return

        user_id = self.user_list.item(selected_item, "values")[0]
        if self.user_manager.remove_user(user_id):
            messagebox.showinfo("Succès", "Utilisateur supprimé avec succès.")
            self.refresh_users()
        else:
            messagebox.showerror("Erreur", "Erreur lors de la suppression de l'utilisateur.")

if __name__ == "__main__":
    wsdl_url = "http://localhost:8000/?wsdl"

    LoginWindow(wsdl_url).mainloop()
