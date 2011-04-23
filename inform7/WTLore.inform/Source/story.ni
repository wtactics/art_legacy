"WTLore" by "snowdrop".

Chapter 0 - The technicalities

The story genre is "Fantasy".
The story headline is "A part of an interactive Background Story".
The story creation year is 2011.
The release number is 0.
The story description is "A prominent duke throws a banquette in his castle. All members of the higher strata are there. The Empire has dispatechd you, one one of it's up and coming agents, to gather intel and execute a delicate mission. Are you up for the task? "


Release along with an interpreter.
Release along with the source text.
Index map with EPS file.


Include Basic Help Menu by Emily Short.
Include Basic Screen Effects by Emily Short.


When play begins:
     choose row 1 in Table of Basic Help Options;
     now description entry is "This modest piece of interactive fiction is played out in the setting of WTactics.org and has been written to give you a richer understanding and experience of the universe in which WT is set. The game can stand on its own without any prior knowledge about WTactics.org or CCG:s."


Table of Basic Help Options (continued)
title	subtable	description
"Hints"	Table of Hints	--
"Settings"	Table of Setting Options	--
"Contacting the author"	--	"If you have any difficulties with [story title] or would like to add to it, please contact me at snowdrop@WTactics.org"


Table of Hints
title	subtable	description	toggle
"What do I do with the string?"	Table of String Hints	""	hint toggle rule

Table of String Hints
hint	used
"Remember that you know how to macrame."	a number
"Oh, come now, isn't it obvious?"



Chapter 1 - The Journey

Every turn when the player is in the carriage:
	if a random chance of 1 in 3 succeeds, say "[one of]The carriage wheels rumble over a small hole in the ground.[or]You feel how the carriage is turning in a steep curve, aking you lean into that direction.[or]You are getting closer by the minute and will soon get to prove yourself.[then at random]". 

The carriage is a room. The carriage is east of the yard. "The black carriage in which you arrived in is parked at the side, in front of the entrance. The carriage is small, yet of finest comfort."



 The scroll container is a openable closed container in the carriage. "You notice a scroll container lying at the empty seat in front of you. It's of thick leather, looks unworn and [if we have not opened the scroll container]hasn't been opened yet judging from the unbroken Imperial seal. [otherwise]the Imperial seal on it has been broken.[end if]"

The description of the scroll container is "It gives an impression of being important due to it wearing the wax sealing with the Imperial sigill. Rumor has it only the emperor, his nearest of kin and a few selected advisors are authorised to use it. [if we have not examined the scroll container] The container is of good craftmanship, a cylindrical shape made of just a single piece of leather to better protect its content in case it would be exposed to water. [end if]"

A letter is inside the scroll container. "Within the scroll container is a letter from the Imperial Guard."

The description of the letter is "[bold type]Orders by the Imperial Five ......[roman type]I. The gaian representativ[paragraph break]II. Banner spy."


Instead of going west from the carriage:
	If we have not examined the letter:
		say "On second thought you don't want to leave the carriage just yet. First you would want to read your orders for the evening events.";	
	if we have examined the letter:
		Say "You memorise the content of the letter and make sure to carefully tear it into tiny pieces, discarding them in the evening breez as you open the door of the carriage and step down onto sand. The carriage takes its leave.";
		move the player to the Yard.
	
	
Instead of going east from the Yard to the carriage, say "There is nothing to the east - your carriage left after you arrived at the castle premises, and you're to self-loving to dare leave before completing your mission."		

Chapter 2 - The Banquet 

The Yard is a room. The yard is east of the castle hallway. "You have never witnessed such a marvelous garden before."
 



	test msg with "take scroll container / w / open container / examine letter/w".









The Castle Hallway room is west of The Yard. "You find yourself standing in the castle entrance. It has a very high ceiling with what seems to be sections of thick heavy glass, revealing a clear night with stars incognito. [if unvisited]There is a dim light at the east end of the passage.[end if]"

A wardrobe is here. "There is a small wicker cage discarded nearby."








