#!/bin/bash

../yii util findReadyStart
for ((a=1; a <= 40; a++))
do
  ../yii util findReady
done

../yii yandex makeName
../yii yandex makeSeoUrl
../yii yandex makeTitleProductStart
../yii yandex makeTitleProduct
../yii yandex makeTitleProduct
../yii yandex makeTitleProduct
../yii yandex makeTitleCategory
../yii yandex yandexMarket


