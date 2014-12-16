#!/bin/sh
PWD=`pwd`
export PATH=$PATH:$PWD/bin
export DYLD_LIBRARY_PATH=$DYLD_LIBRARY_PATH:$PWD/lib
protoc $1 --$2_out $3