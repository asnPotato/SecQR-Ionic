from LSBSteg import *
import cv2
import datetime
import sys
"""LSB Steg embeding"""
steg = LSBSteg(cv2.imread("upload/qrcode.png"))

if len(sys.argv) < 2:
  print("Unique ID is missing.")
  sys.exit()

id = sys.argv[1]

dandt =  datetime.datetime.now()
date_time_str = dandt.strftime("%Y-%m-%d %H:%M:%S")
message = str(str(id) + " " + date_time_str)
img_encoded = steg.encode_text(message)
print(id,dandt)
cv2.imwrite("upload/qrcodeSTG.png", img_encoded)
