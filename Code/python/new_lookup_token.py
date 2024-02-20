import requests
import json
import datetime
import base64

TOKEN_URL = "https://api.sandbox.ebay.com/identity/v1/oauth2/token"
CLIENT_ID = "JacobWeb-sa-SBX-ad28d4866-82b67f80"
CLIENT_SECRET = "SBX-d28d4866bdcf-023f-473a-a4d6-06c8"

prepared_string = CLIENT_ID + ":" + CLIENT_SECRET
prepared_string_bytes = prepared_string.encode("ASCII")

encoded_string = base64.b64encode(prepared_string_bytes)
final_string = encoded_string.decode("ASCII")

headers = {
    "Content-Type": "application/x-www-form-urlencoded",
    "Authorization": "Basic " + final_string
}

body = {
    "grant_type": "client_credentials",
    "scope": "https://api.ebay.com/oauth/api_scope/buy.item.bulk"
}

resp = requests.post(TOKEN_URL, headers=headers, data=body)
data = json.loads(resp.content)
global EBAY_TOKEN
EBAY_TOKEN = data['access_token']
global EXPIRES 
EXPIRES = datetime.datetime.now() - datetime.timedelta(seconds=14400) + datetime.timedelta(seconds=7200)

print(EBAY_TOKEN)
print(EXPIRES)
