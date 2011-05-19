"WTLore" by "snowdrop".

Chapter 0 - The technicalities

The story genre is "Fantasy".
The story headline is "Part one of an interactive background story".
The story creation year is 2011.
The release number is 0.
The story description is "A prominent duke throws a banquette in his castle. All members of the higher strata are there. The Empire has dispatcehd you, one one of it's up and coming agents, to gather intel and execute a delicate mission. Are you up for the task? "

Release along with the source text.
Release along with an interpreter.
Index map with EPS file.


Include Basic Help Menu by Emily Short.
Include Basic Screen Effects by Emily Short.


When play begins:
     choose row 1 in Table of Basic Help Options;
     now description entry is "This modest piece of interactive fiction is played out in the setting of WTactics.org and has been written to give you a richer understanding and experience of the universe in which WTactics is set. The fiction has been written so that it can stand on its own without any prior knowledge at all about WTactics."


Table of Basic Help Options (continued)
title	subtable	description
"Hints"	Table of Hints	--
"Settings"	Table of Setting Options	--
"Contacting the author"	--	"If you have any difficulties with [story title] or would like to contribute to it, please write us now at contact@WTactics.org"


Table of Hints
title	subtable	description	toggle
"What do I do with the string?"	Table of String Hints	""	hint toggle rule

Table of String Hints
hint	used
"Remember that you know how to macrame."	a number
"Oh, come now, isn't it obvious?"



Chapter 1 - The Journey

The carriage is a room. The description of the carriage is "[if unvisited]When boarding the carriage you were impressed by the seemingly high standard in which you would get to travel: [end if]It's pitch black construction is heavily decorated with flower resembling golden patterns, it is spacious and of finest standard in every aspect. There are two comfortable wide seats that face each other in it, one of which you occupy. The carriage walls are covered with quilted dark red silk. The door is locked from the outside."




The scroll container is a openable closed container in the carriage. "[if the carriage is unvisited]You notice a scroll container lying at the empty seat in front of you.[else]The scroll container is still here.[end if] It's of thick leather, looks newly made and [if we have not opened the scroll container]hasn't been [italic type]opened[roman type] yet judging by the looks of the unbroken seal. [otherwise]is open.[end if]"

The description of the scroll container is "It gives an impression of being important due to it [if we have not opened the scroll container]wearing the wax sealing with the Imperial sigill.[else]having worn the sigill.[end if] Rumour has it only the emperor, his nearest of kin and a few selected advisers are authorised to use it.[if we have not examined the scroll container] The container is of good craftsmanship. It has a cylindrical shape made of just a single piece of leather to better protect its content in case it would be exposed to water. [end if]"

A letter is a thing. "Within it there's a letter from the Imperial Guard."
The letter is inside the scroll container.

The description of the letter is "Your mission is... TODO | Letter text here, outlining some of the initial missions the reader will get."



Some pieces of wax are things. "On the seat there are some small red pieces of wax. They used to guarantee the authenticity of the message."

The description of some pieces of wax is "They used to make up the seal of the scroll container and are the remainder of the sigill, which is beyond recognition."
	
	
after opening the scroll container:
	say "The carriage is finally beginning to slow down as you reach for the scroll container. 
	The broken wax falls of it in small pieces on the seat. Seeing this, you hesitate a moment, as if the container wasn't meant for you even though you know it is. It was placed there by trusted messenger, the word directly sent from the court of his Imperial highness. 
[paragraph break]You think about it for a second and realise you stopped for a moment not because you would violate the law of secrecy by resting eyes where they didn't belong, but because you know how important the content of the container is.[paragraph break][if the letter is in the scroll container]Your orders are in the container, waiting to be read.[otherwise] As you expected, it is still empty as you left it.[end if]";
	Now the wax is in the carriage.
			
		
instead of closing the scroll container:
	say "It serves no purpose to close it now that the sigill on it has been broken into several pieces."

	

Understand "piece/sigill/seal/emblem" as some pieces of wax.
Understand "tube/cylinder/case/leather" as the scroll container.
Understand "paper/note/message/orders/scroll/order" as the letter.

Every turn when the player is in the carriage:
	if the container is closed:
		if a random chance of 1 in 2 succeeds:
			say "[one of]You're bored by the long travels and have a tense feeling.[or]Will this journey never end? Time goes slowly by.[or]The carriage wheels rumble over a small hole in the ground.[or]You feel how the carriage is turning in a steep curve, making you lean into that direction.[or]The sound from the fast turning carriage wheels makes it hard to focus.[or]The two black horses pulling the carriage makes it rock slightly back and forth. It seems you travel with haste. [or]Some undistinguishable sounds of wild animals penetrate the wooden walls of the carriage. You must be passing the forests.[or]You are getting closer by the minute.[or]Very soon you will get to prove yourself.[then at random]";
	If we have examined the letter:
		Say "Ending the reading of the letter you realise the horses have halted. You have arrived.[paragraph break]It's time to perform the role of your life. You couldn't believe it when it happened: The huge honour, to be chosen for the task at hand. This would surely be the single most important assignment you could have dreamt of ever getting.[if the player is carrying the container] In order to conceal your true association with the Empire you decide it's best to leave the container in the carriage, keeping it's sigill out of sight.[end if][paragraph break]You memorise the content of the letter and make sure to carefully tear it into tiny random pieces beyond recognition. As you open the door you discard them in the evening breeze while stepping down onto the sand.[paragraph break]The carriage starts moving again, this time with no passenger on-board[if the scroll is in the carriage] other than the empty container left behind by you[end if]. You can't help wondering if this was your last ride given the dangers that lay ahead, if it is the final destination you would ever reach.";
		remove the letter from play;
		remove the scroll container from play;
		move the player to the Yard.
	
	
Chapter 2 - The Banquet 

The Yard is a room. The yard is east of the castle hallway. "There are three fountains here in a row."
 
The outer garden is a room. "You have never witnessed such a marvellous garden before."

The outdoors area is a region. The yard and the outer garden are in the outdoors.



	test 1 with "look /  i / open scroll container / close tube / open tube / look / take pieces / read letter".
	
	test wax with "l / open scroll container / l / take emblem / drop emblem / take sigill / i"


The Castle Hallway is west of The Yard. "You find yourself standing in the castle entrance. It has a sky high ceiling with what seems to be sections of thick heavy glass, revealing a clear night with shy stars incognito.[paragraph break][if unvisited]There is a dim light finding its way to you from the east corridor.[otherwise]You can only go east from here.[end if]"









