#!/bin/bash

../yii import import_0
./imp.sh
../yii util setCatIsReady
../yii import linkCat
./lnkColor.sh
../yii util handleProducts

# ../yii util setCatIsReady
# ../yii import linkCat
# ./lnkColor.sh
# ../yii util checkYarnStart
# for ((a=1; a <= 20; a++))  
# do
#   ../yii util checkYarn
# done      

# ../yii util cleanStoreStart
# for ((a=1; a <= 20; a++))
# do
#   ../yii util cleanStore
# done

# ../yii util noPrice2ArchStart
# for ((a=1; a <= 20; a++))
# do
#   ../yii util noPrice2Arch
# done

