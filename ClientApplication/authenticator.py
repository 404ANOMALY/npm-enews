import zeep

class Authenticator:
    def __init__(self, wsdl):
        self.client = zeep.Client(wsdl=wsdl)

    def authenticate(self, username, password):
        try:
            response = self.client.service.authenticate(username, password)
            print(f"Response from service: {response}")
            if response:
                return response  # Retourne la réponse directement
            else:
                return None
        except Exception as e:
            print(f"Erreur lors de l'authentification : {e}")
            return None