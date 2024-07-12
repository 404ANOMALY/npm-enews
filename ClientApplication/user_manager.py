import zeep
class UserManager:
    def __init__(self, wsdl_url, token):
        self.client = zeep.Client(wsdl=wsdl_url)
        self.token = token

    def list_users(self):
        try:
            response = self.client.service.list_users(self.token)
            if response:
                return response
            else:
                return []
        except Exception as e:
            print(f"Erreur lors de la récupération des utilisateurs : {e}")
            return []

    def add_user(self, user):
        try:
            return self.client.service.add_user(user)
        except Exception as e:
            print(f"Erreur lors de l'ajout de l'utilisateur : {e}")
            return False

    def update_user(self, user):
        try:
            return self.client.service.update_user(user)
        except Exception as e:
            print(f"Erreur lors de la mise à jour de l'utilisateur : {e}")
            return False

    def remove_user(self, user_id):
        try:
            return self.client.service.remove_user(user_id)
        except Exception as e:
            print(f"Erreur lors de la suppression de l'utilisateur : {e}")
            return False