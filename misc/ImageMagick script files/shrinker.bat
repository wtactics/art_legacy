for %%f in (*.png) do ( convert "%%f" -sharpen 0x1 -resize 304x420! "%%~nf.png" )
convert -size 304x420 xc:none -draw "roundrectangle 0,0,304,420,10,10" -channel A -blur 0.6 mask.tga
for %%f in (*.png) do ( convert "%%f" -matte mask.tga -compose DstIn -composite "%%~nf.png" )