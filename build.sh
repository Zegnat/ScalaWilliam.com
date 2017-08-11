#!/bin/bash

mkdir -p out

mkdir -p out/1404/an-introduction-to-scala/
php 1404/an-introduction-to-scala/index.php > out/1404/an-introduction-to-scala/index.html

rsync -a 1412 out/
mkdir -p out/1412/amazon-wishlist-api/
php 1412/amazon-wishlist-api/index.php > out/1412/amazon-wishlist-api/index.html

mkdir -p out/1406/sygments/
php 1406/sygments/index.php > out/1406/sygments/index.php

mkdir -p out/1406/xml-processing-in-scala
php 1406/xml-processing-in-scala/index.php > out/1406/xml-processing-in-scala/index.html

rsync -a cv out/
php out/cv/index.php > out/cv/index.html

rsync -a shared --exclude '*.html' out/

rsync -a index out/
php index.php > out/index.html

mkdir -p out/content/
php content/index.php > out/content/index.html

mkdir -p out/1607/quick-scala-tutorial
php 1607/quick-scala-tutorial/index.php > out/1607/quick-scala-tutorial/index.html

mkdir -p out/1609/feature-switches-agile-scala-jmx/
cp 1609/feature-switches-agile-scala-jmx/index.html out/1609/feature-switches-agile-scala-jmx/index.html
cp 1609/feature-switches-agile-scala-jmx/*.png out/1609/feature-switches-agile-scala-jmx/

mkdir -p out/1705/scala-android-opportunity
cp 1705/scala-android-opportunity/index.html out/1705/scala-android-opportunity/

mkdir -p out/scala-for-2017
php scala-for-2017/index.php > out/scala-for-2017/index.html
cp scala-for-2017/videos-stream-icon.svg out/scala-for-2017/

mkdir -p out/essential-sbt
cp essential-sbt/index.html out/essential-sbt/index.html

mkdir -p out/tournament-tree
cp tournament-tree/index.html out/tournament-tree/index.html

mkdir -p out/scala-json
cp scala-json/index.html out/scala-json/index.html

mkdir -p out/scala-native-libpcap
cp scala-native-libpcap/index.html out/scala-native-libpcap/index.html
cp scala-native-libpcap/*.svg scala-native-libpcap/*.png out/scala-native-libpcap/

mkdir -p out/most-important-streaming-abstraction
cp most-important-streaming-abstraction/index.html out/most-important-streaming-abstraction/index.html

mkdir -p out/scala-nashorn-interaction
cp scala-nashorn-interaction/index.html out/scala-nashorn-interaction/index.html

mkdir -p out/1612/limit-degrees-of-freedom
cp 1612/limit-degrees-of-freedom/index.html out/1612/limit-degrees-of-freedom/
cp 1612/limit-degrees-of-freedom/*.png out/1612/limit-degrees-of-freedom/
cp 1612/limit-degrees-of-freedom/*.jpeg out/1612/limit-degrees-of-freedom/

mkdir -p out/1704/certifications-im-considering
cp 1704/certifications-im-considering/index.html out/1704/certifications-im-considering/

mkdir -p out/1705/greenfield-technical-debt
cp 1705/greenfield-technical-debt/index.html out/1705/greenfield-technical-debt/
cp 1705/greenfield-technical-debt/dont-be-like-this.jpeg out/1705/greenfield-technical-debt/

mkdir -p out/unit-testing-is-simple
php embed-code.php unit-testing-is-simple/index.html | php add-meta.php > out/unit-testing-is-simple/index.html

mkdir -p out/1704/2017-freelance-development-platforms
cp 1704/2017-freelance-development-platforms/index.html out/1704/2017-freelance-development-platforms/index.html

mkdir -p out/1701/nashorn-cli-use-case
php embed-code.php 1701/nashorn-cli-use-case/index.html > out/1701/nashorn-cli-use-case/index.html
cp 1701/nashorn-cli-use-case/*.png out/1701/nashorn-cli-use-case/

mkdir -p out/1705/advantages-of-scala-over-kotlin
cp 1705/advantages-of-scala-over-kotlin/index.html out/1705/advantages-of-scala-over-kotlin/index.html

mkdir -p out/sitemap/
php sitemap.php > out/sitemap/index.html
php 404.php > out/404.html
php sitemap-xml.php > out/sitemap.xml
cp robots.txt out/robots.txt

php add-analytics.php $(find out -iname '*.html'|grep -v shared)
