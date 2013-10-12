#!/bin/bash

IFILE=$1
FILE=$IFILE
DEST=$2
filename=$(basename "$FILE")
extension="${filename##*.}"
filename="${filename%.*}"

if [ "$extension" = "mail" ]
then
	cd fetched
	munpack "../$FILE"
	cd ..
	for attachment in fetched/*
	do
		./toround.sh "$attachment" $DEST
	done
	exit 0
fi

if [ "$extension" = "docx" ]
then
	#lowriter --convert-to doc "$FILE" "bin/$filename.doc" --invisible
	#unoconv --output="bin/$filename.doc" "$FILE"
	#mv "$filename.doc" "bin/$filename.doc"
	cp "$FILE" "bin/$filename.docx"
	docx2txt.sh "bin/$filename.docx"
	cat "bin/$filename.txt" | sed -e 's/^[ \t]*//' | sed '/^$/d' | awk '/^\014/{sub("\014","")}1' > "bin/$filename.preround"
	rm "bin/$filename.txt"
	FILE="bin/$filename.preround"
	extension=preround
fi

if [ "$extension" = "doc" ]
then
	catdoc -w "$FILE" | awk '/^\014/{sub("\014","")}1' | sed 's/[^[:print:]]/\n/' | sed -e 's/^[ \t]*//' | sed 's/^[ \t]*$//' | sed '/^$/d' > "bin/$filename.preround"
	FILE="bin/$filename.preround"
	extension=preround
fi

if [ "$extension" = "odt" ]
then
	cp "$FILE" "bin/$filename.odt"
	odt2txt "bin/$filename.odt"
	cat "bin/$filename.txt" | sed -e 's/^[ \t]*//' | sed '/^$/d' | awk '/^\014/{sub("\014","")}1' > "bin/$filename.preround"
	rm "bin/$filename.txt"
	FILE="bin/$filename.preround"
	extension=preround
fi

if [ "$extension" = "pdf" ]
then
	cp "$FILE" "bin/$filename.pdf"
	pdftotext "bin/$filename.pdf" #-layout
	#cat "bin/$filename.txt" | sed 's/[^[:print:]]/\n/' | sed -e 's/^[ \t]*//;s/[ \t]*$//' | awk ' /^$/ { print; } /./ { printf("%s ", $0); }' | sed 's/X)/\nX)/g' | sed 's/Y)/\nY)/g' | sed 's/Z)/\nZ)/g' | sed -e 's/($//g' | sed 's/$(//g' | sed '/^$/d' > "bin/$filename.preround"
	# | sed '/^\(W\)/s/\(X\)/\\n\(X\)/g' | sed '/^\(X\)/s/\(Y\)/\\n\(Y\)/g' | sed '/^\(Y\)/s/\(Z\)/\\n\(Z\)/g'
	cat "bin/$filename.txt" | sed 's/[^[:print:]]/\n/' | sed -e 's/^[ \t]*//;s/[ \t]*$//' | sed '/^$/d' | awk '/^\014/{sub("\014","")}1' > "bin/$filename.preround"
	rm "bin/$filename.txt"
	FILE="bin/$filename.preround"
	#exit 0
	extension=preround
fi

if [ "$extension" = "tex" ]
then
	echo "ERROR: LaTeX support not yet implemented"
	mv "$IFILE" "unparsable"
	exit -1
fi

if [ "$extension" = "txt" ]
then
	cat "$FILE" | sed -e 's/^[ \t]*//' | sed '/^$/d' | awk '/^\014/{sub("\014","")}1' > "bin/$filename.preround"
	FILE="bin/$filename.preround"
	extension=preround
fi

if [ "$extension" = "preround" ]
then
	#newfilename=$(dirname $FILE | sed -i 's,/,-,g')
	./compile < "$FILE" > "$DEST/$filename.round"
	rm "bin/$filename.preround"
	extension=round
fi

if [ "$extension" = "round" ]
then
	contents=$(cat "$DEST/$filename.round")
	if [ "$contents" = "Round []" ]
	then
		mv "$IFILE" "unparsed"
		rm "$DEST/$filename.round"
		exit 0
	else
		mv "$IFILE" "parsed"
		exit 0
	fi
else
	echo "ERROR: No support for files with extension '.$extension'"
	mv "$IFILE" "unparsable"
	exit -1
fi
