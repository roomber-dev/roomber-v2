# importing the requests library
#import requests

# defining the api-endpoint
#API_ENDPOINT = "http://localhost/post.php"

# your API key here


# data to be sent to api
#data = {
#    "text": "",
#    "Accept": "*/*",
#    "Accept-Encoding": "gzip, deflate, br",
#    "Accept-Language": "en-US,en;q=0.9",
#    "Connection": "keep-alive",
#    "Content-Length": "9",
#    "Content-Type": "application/x-www-form-urlencoded",
#    "Cookie": "PHPSESSID=q3h3c9jjf9ijvrl6csda8bu4u9",
#    "Host": "localhost",
#    "Origin": "http://localhost",
#    "Referer": "http://localhost/",
#    "sec-ch-ua": '"Opera GX";v="77", "Chromium";v="91", ";Not A Brand";v="99"',
#    "sec-ch-ua-mobile": "?0",
#    "Sec-Fetch-Dest": "empty",
#    "Sec-Fetch-Mode": "cors",
#    "Sec-Fetch-Site": "same-origin",
#    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.164 Safari/537.36 OPR/77.0.4054.298",
#    "X-Requested-With": "XMLHttpRequest"
#}
#
## sending post request and saving response as response object
#r = requests.post(url=API_ENDPOINT, data=data)
#
## extracting response text
#pastebin_url = r.text
#print("The response is: %s" % pastebin_url)


import requests
from requests.structures import CaseInsensitiveDict

url = "https://reqbin.com/echo/post/json"

headers = CaseInsensitiveDict()
headers["Accept"] = "application/json"
headers["Content-Type"] = "application/json"

data = """
{
  "Id": 78912,
  "Customer": "Jason Sweet",
  "Quantity": 1,
  "Price": 18.00
}
"""



import threading

def printit():
  threading.Timer(0.1, printit).start()  
  resp = requests.post('188.147.103.47', headers=headers, data=data)
  print(resp.text)

printit()

# continue with the rest of your code

