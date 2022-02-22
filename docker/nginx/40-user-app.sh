#!/bin/bash
groupadd -r app -g 1000 && useradd -u 1000 -r -g app -m -d /home/app -s /bin/bash -c "App user" app
