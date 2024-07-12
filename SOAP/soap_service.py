from spyne import Application, rpc, ServiceBase, Integer, Unicode, Boolean, Array, ComplexModel
from spyne.protocol.soap import Soap11
from spyne.server.wsgi import WsgiApplication
from datetime import datetime
from spyne.model.fault import Fault
import mysql.connector

# Connexion à la base de données MySQL
conn = mysql.connector.connect(user='mglsi_user', password='passer', host='localhost', port='3306', database='mglsi_news')
cursor = conn.cursor(dictionary=True)


class User(ComplexModel):
    id = Integer
    username = Unicode
    password = Unicode
    email = Unicode
    role = Unicode
    created_at = Unicode


class UserService(ServiceBase):
    @rpc(Unicode, Unicode, _returns=User)
    def authenticate(ctx, username, password):
        cursor.execute("SELECT * FROM users WHERE username=%s AND password=%s", (username, password))
        user = cursor.fetchone()

        if user and user['role'] == 'admin':
            return User(id=str(user['id']), username=user['username'], password=user['password'], email=user['email'],
                        role=user['role'])
        else:
            raise Fault(faultcode='Server', faultstring='Invalid credentials')

    @rpc(Unicode, _returns=Array(User))
    def list_users(ctx, token):
        users = []
        try:
            connection = mysql.connector.connect(
                host='localhost',
                port='3306',
                user='mglsi_user',
                password='passer',
                database='mglsi_news'
            )
            cursor = connection.cursor()
            cursor.execute("SELECT id, username, password, email, role, created_at FROM users")
            result = cursor.fetchall()
            for row in result:
                user = User(
                    id=row[0],
                    username=row[1],
                    password=row[2],
                    email=row[3],
                    role=row[4],
                    created_at=row[5].strftime('%Y-%m-%d %H:%M:%S') if isinstance(row[5], datetime) else row[5]
                )
                users.append(user)
        except mysql.connector.Error as err:
            print(f"Erreur lors de la connexion à la base de données : {err}")
        finally:
            cursor.close()
            connection.close()
        return users

    @rpc(User, _returns=Boolean)
    def add_user(ctx, user):
        try:
            connection = mysql.connector.connect(
                host='localhost',
                port='3306',
                user='mglsi_user',
                password='passer',
                database='mglsi_news'
            )
            cursor = connection.cursor()
            cursor.execute(
                "INSERT INTO users (username, password, email, role, created_at) VALUES (%s, %s, %s, %s, %s)",
                (user.username, user.password, user.email, user.role, datetime.now())
            )
            connection.commit()
            return True
        except mysql.connector.Error as err:
            print(f"Erreur lors de l'ajout de l'utilisateur : {err}")
            return False
        finally:
            cursor.close()
            connection.close()

    @rpc(User, _returns=Boolean)
    def update_user(ctx, user):
        try:
            connection = mysql.connector.connect(
                host='localhost',
                port='3306',
                user='mglsi_user',
                password='passer',
                database='mglsi_news'
            )
            cursor = connection.cursor()
            cursor.execute(
                "UPDATE users SET username=%s, password=%s, email=%s, role=%s WHERE id=%s",
                (user.username, user.password, user.email, user.role, user.id)
            )
            connection.commit()
            return True
        except mysql.connector.Error as err:
            print(f"Erreur lors de la mise à jour de l'utilisateur : {err}")
            return False
        finally:
            cursor.close()
            connection.close()

    @rpc(Integer, _returns=Boolean)
    def remove_user(ctx, user_id):
        try:
            connection = mysql.connector.connect(
                host='localhost',
                port='3306',
                user='mglsi_user',
                password='passer',
                database='mglsi_news'
            )
            cursor = connection.cursor()
            cursor.execute("DELETE FROM users WHERE id=%s", (user_id,))
            connection.commit()
            return True
        except mysql.connector.Error as err:
            print(f"Erreur lors de la suppression de l'utilisateur : {err}")
            return False
        finally:
            cursor.close()
            connection.close()

def authenticate_token(token):
    # Vérifie si le jeton d'authentification est valide
    return token == 'valid-token'  # À remplacer par votre propre mécanisme d'authentification


application = Application([UserService], 'spyne.examples.hello.soap',
                          in_protocol=Soap11(validator='lxml'),
                          out_protocol=Soap11())
wsgi_application = WsgiApplication(application)

if __name__ == '__main__':
    from wsgiref.simple_server import make_server

    server = make_server('127.0.0.1', 8000, wsgi_application)
    print("Serveur SOAP démarré sur http://127.0.0.1:8000/")
    print("wsdl is at: http://localhost:8000/?wsdl")

    print("Pour arrêter le serveur, faites Ctrl+C")
    server.serve_forever()
