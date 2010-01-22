#!/usr/bin/env bash

ROOT=/var/www/roolo-Math

cd $ROOT
rm -v Questions/[^q]*.jpg 
curl -g http://baci.oise.utoronto.ca:8070/roolo-standalone-server/DeleteAll
cp -v Questions/q*.jpg questionsSource/
curl -g http://roolo-math.localhost:8081/src/php/script/script_moveRenameCreateQuestionsObject.php

