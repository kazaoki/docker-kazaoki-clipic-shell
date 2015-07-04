#!/bin/sh
docker rm -f "kazaoki-clipic" >/dev/null 2>&1
CLIPIC_WIDTH=$(tput cols)
if [[ $2 != "" ]]; then
	CLIPIC_WIDTH=$2
fi
if [[ "$1" =~ ^https?\:\/\/ ]]; then
	echo "$1" | docker run -i --rm -e CLIPIC_WIDTH=$CLIPIC_WIDTH kazaoki/clipic
elif [[ $1 != "" ]]; then
	cat $1 | docker run -i --rm -e CLIPIC_WIDTH=$CLIPIC_WIDTH kazaoki/clipic
fi
