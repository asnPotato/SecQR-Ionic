from LSBSteg import *
import cv2

"""Read the hidden data to be sent to dbwrite """
def get_urldec():
    im = cv2.imread("upload/qrcodeSTG.png")
    steg = LSBSteg(im)
    stgmsg = steg.decode_text()
        
    print(stgmsg)

get_urldec()
