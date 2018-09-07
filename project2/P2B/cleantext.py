#!/usr/bin/env python

"""Clean comment text for easier parsing."""

from __future__ import print_function

import re
import string
import argparse
import sys
import json


__author__ = ""
__email__ = ""

# Some useful data.
_CONTRACTIONS = {
    "tis": "'tis",
    "aint": "ain't",
    "amnt": "amn't",
    "arent": "aren't",
    "cant": "can't",
    "couldve": "could've",
    "couldnt": "couldn't",
    "didnt": "didn't",
    "doesnt": "doesn't",
    "dont": "don't",
    "hadnt": "hadn't",
    "hasnt": "hasn't",
    "havent": "haven't",
    "hed": "he'd",
    "hell": "he'll",
    "hes": "he's",
    "howd": "how'd",
    "howll": "how'll",
    "hows": "how's",
    "id": "i'd",
    "ill": "i'll",
    "im": "i'm",
    "ive": "i've",
    "isnt": "isn't",
    "itd": "it'd",
    "itll": "it'll",
    "its": "it's",
    "mightnt": "mightn't",
    "mightve": "might've",
    "mustnt": "mustn't",
    "mustve": "must've",
    "neednt": "needn't",
    "oclock": "o'clock",
    "ol": "'ol",
    "oughtnt": "oughtn't",
    "shant": "shan't",
    "shed": "she'd",
    "shell": "she'll",
    "shes": "she's",
    "shouldve": "should've",
    "shouldnt": "shouldn't",
    "somebodys": "somebody's",
    "someones": "someone's",
    "somethings": "something's",
    "thatll": "that'll",
    "thats": "that's",
    "thatd": "that'd",
    "thered": "there'd",
    "therere": "there're",
    "theres": "there's",
    "theyd": "they'd",
    "theyll": "they'll",
    "theyre": "they're",
    "theyve": "they've",
    "wasnt": "wasn't",
    "wed": "we'd",
    "wedve": "wed've",
    "well": "we'll",
    "were": "we're",
    "weve": "we've",
    "werent": "weren't",
    "whatd": "what'd",
    "whatll": "what'll",
    "whatre": "what're",
    "whats": "what's",
    "whatve": "what've",
    "whens": "when's",
    "whered": "where'd",
    "wheres": "where's",
    "whereve": "where've",
    "whod": "who'd",
    "whodve": "whod've",
    "wholl": "who'll",
    "whore": "who're",
    "whos": "who's",
    "whove": "who've",
    "whyd": "why'd",
    "whyre": "why're",
    "whys": "why's",
    "wont": "won't",
    "wouldve": "would've",
    "wouldnt": "wouldn't",
    "yall": "y'all",
    "youd": "you'd",
    "youll": "you'll",
    "youre": "you're",
    "youve": "you've"
}

# You may need to write regular expressions.

def sanitize(text):
    """Do parse the text in variable "text" according to the spec, and return
    a LIST containing FOUR strings 
    1. The parsed text.
    2. The unigrams
    3. The bigrams
    4. The trigrams
    """

    # YOUR CODE GOES BELOW:
    # Replace \n and \t characters with empty string
    parsed_text = text.replace("\\n", "").replace("\\t", "")

    # Remove reddit formatted URLs and surrounding punctuation
    parsed_text = re.sub('(\[)(.*?)(\])(\(.*?\))', r'\2', parsed_text)
    # Remove http/https URLs
    parsed_text = re.sub(r'http\S+', '', parsed_text)
    # Separate punctuation and special characters by spaces, unless it precedes a letter or number
    parsed_text = re.sub('([^a-zA-Z0-9])([^a-zA-Z0-9])', r' \1 \2 ', parsed_text)
    # Remove punctuation/special chars that aren't considered an end of phrase marker or don't precede a letter or number
    parsed_text = re.sub('([^ a-zA-Z0-9.,!?:;])([^a-zA-Z0-9])', r'\2', parsed_text)
    # Remove punctuation/special chars that come before a letter/number
    parsed_text = re.sub('( )([^ a-zA-Z0-9.,!?:;])', r'\1', parsed_text)
    # Remove punctuation/special chars located at the very beginning of the string
    parsed_text = re.sub('^([^ a-zA-Z0-9.,!?:;\'])(.*)', r'\2 ', parsed_text)
    # Correctly space end punctuation that occurs at the end of the string
    parsed_text = re.sub('([!?,.;:])$', r' \1', parsed_text)
    # Condense instances of multiple spaces to one space
    parsed_text = re.sub(' +', ' ', parsed_text)\
    # Convert all of the text to lowercase
    parsed_text = parsed_text.lower()

    unigrams = create_unigrams(parsed_text)
    bigrams = create_bigrams(parsed_text)
    trigrams = create_trigrams(parsed_text)

    return [unigrams, bigrams, trigrams] # add parsed_text for output if necessary, removed for P2B

def create_unigrams(text):
	unigrams = ''
	text = text.split()
	for token in text:
		if (token != ',' and token != '?' and token and '!' and token != '.' and token != ':' and token != ';'):
			unigrams = unigrams + token + ' '
	return unigrams		

def create_bigrams(text):
	bigrams = ''
	text = text.split()
	for i in range(len(text)-2):
		if (text[i] != ',' and text[i] != '?' and text[i] and '!' and text[i] != '.' and text[i] != ':' and text[i] != ';'):
			if (text[i+1] != ',' and text[i+1] != '?' and text[i+1] and '!' and text[i+1] != '.' and text[i+1] != ':' and text[i+1] != ';'):
				bigrams = bigrams + text[i] + '_' + text[i+1] + ' '
	return bigrams

def create_trigrams(text):
	trigrams = ''
	text = text.split()
	for i in range(len(text)-3):
		if (text[i] != ',' and text[i] != '?' and text[i] and '!' and text[i] != '.' and text[i] != ':' and text[i] != ';'):
			if (text[i+1] != ',' and text[i+1] != '?' and text[i+1] and '!' and text[i+1] != '.' and text[i+1] != ':' and text[i+1] != ';'):
				if (text[i+2] != ',' and text[i+2] != '?' and text[i+2] and '!' and text[i+2] != '.' and text[i+2] != ':' and text[i+2] != ';'):
					trigrams = trigrams + text[i] + '_' + text[i+1] + '_' + text[i+2] + ' '
	return trigrams

if __name__ == "__main__":
    # This is the Python main function.
    # You should be able to run
    # python cleantext.py <filename>
    # and this "main" function will open the file,
    # read it line by line, extract the proper value from the JSON,
    # pass to "sanitize" and print the result as a list.

    # YOUR CODE GOES BELOW.
	if len(sys.argv) >= 2:
		filename = sys.argv[1]
	else:
		print ('ERROR: format is "python3 cleantext.py <filename>"')
	if filename.lower().endswith('json'):
		table = []
		with open(filename, 'r') as f:
			for line in f:
				table.append(json.loads(line))
			for row in table:
				print(sanitize(row['body']))
	else:
		print ('ERROR: File must be of type json')