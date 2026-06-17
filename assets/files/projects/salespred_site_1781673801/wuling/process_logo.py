import os
import sys
import subprocess

def install(package):
    subprocess.check_call([sys.executable, "-m", "pip", "install", package])

try:
    from PIL import Image
except ImportError:
    install('Pillow')
    from PIL import Image

img_path = r'd:\laragon\www\wuling\assets\images\wuling_log.jpg'
out_path = r'd:\laragon\www\wuling\assets\images\logo_transparent.png'

if os.path.exists(img_path):
    img = Image.open(img_path).convert('RGBA')
    datas = img.getdata()
    newData = []
    for item in datas:
        if item[0] > 200 and item[1] > 200 and item[2] > 200:
            newData.append((255, 255, 255, 0))
        else:
            newData.append((255, 255, 255, 255))
    img.putdata(newData)
    img.save(out_path, 'PNG')
    print("Logo processed successfully.")
else:
    print("Source image not found.")
