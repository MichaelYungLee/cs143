NAME: Michael Lee
EMAIL: michael.y.lee.cs@gmail.com
UID: 904642918

Using both late days for Project 2 on this part. I understand that even then it comes in a few hours late. 

The two main points of difficulty for me were trying to format the text using regular expressions and writing the main function for my program. Up until this point, I have attempted to forego the use and understanding of regular expressions because I just found them too complicated, but I realized that I wouldn't be able to efficiently finish this project component without them. I also ran into errors when trying to use json.load() and did most of my testing through the use of a reading from a .txt file. I managed to get loading from json working as the last part of completing this project segment, and then realized that that portion wouldn't be tested.

I didn't find that I needed to use the CONTRACTIONS dictionary when implementing my sanitize function. My function does remove any characters that are not alphanumeric, punctuation sandwiched between alphanumeric characters, or one of the allowed end punctuation (removes non-ASCII, emojis, etc.) 

Sources:
	I used one regular expression that I found online for parsing out any http/https URLs after I parsed out the Reddit formatted text/URL combinations. The source for that is: https://stackoverflow.com/questions/11331982/how-to-remove-any-url-within-a-string-in-python specifically the third answer that states "re.sub(r'http\S+', '', stringliteral)"

