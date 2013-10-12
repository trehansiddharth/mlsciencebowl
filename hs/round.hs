-- Written by Siddharth Trehan

{-# LANGUAGE NoMonomorphismRestriction #-}

module Round where

	import Data.Char
	import Data.List.Utils
	import Control.Applicative
	import Data.List
	
	data Tag		=	Tag String
						deriving (Show, Eq, Read)
	
	data Subject	=	Biology | Chemistry | Physics | Math | ERSP | Energy
						deriving (Show, Eq, Read)
	
	data Ask		=	Short Tag Subject String String |
						Multiple Tag Subject String [String] String
						deriving (Show, Eq, Read)
	
	getsubject (Short _ subj _ _) = subj
	getsubject (Multiple _ subj _ _ _) = subj
	
	data Question	=	Single Ask | Double Ask Ask
						deriving (Show, Eq, Read)
	
	data AType		= 	ShortAnswer | MultipleChoice
						deriving (Show, Eq, Read)
	
	data QType		=	Tossup | Bonus
						deriving (Show, Eq, Read)
	
	data Round		=	Round [Question]
						deriving (Show, Eq, Read)
	
	{--
	data Symbol		=	None | State QType | Sub Subject | Typ AType | Text String | W String | X String | Y String | Z String | Ans String
						deriving (Show, Eq, Read)
	
	data Token		=	Number Int | Choice Char | Spacer | AChunk AType | QChunk QType | Text
						deriving (Show, Eq, Read)
	
	processall str			= process None [] (str ++ " ")
	
	process ls acc []		= [ls]
	process ls acc (s:str)	= if (samesym getsym ls) then (process getsym (acc ++ [s]) str) else (ls : (process getsym "" (s:str)))
								where
									getsym	| (not . nill) parsestate	= isJust parsestate
											| (not . nill) parsesub		= isJust parsesub
											| (not . nill) parseatype	= isJust parseatype
											| (not . nill) parsetext	= isJust parsetext
											| otherwise					= ls
									
									ac = map toLower acc
									
									linestart = takeUntil (== '\n') . reverse
									
									parsestate	| endswith "\ntossup\n" ac	= Just $ State Tossup
												| endswith "\ntoss-up\n" ac	= Just $ State Tossup
												| endswith "\nbonus\n" ac	= Just $ State Bonus
												| otherwise					= Nothing
									
									parsesub							= if ((samesym ls (State Tossup)) || (samesym ls None))
																			then parsesub' else Nothing									
									parsesub'	| endswith "bio" ac		= Just $ Sub Biology
												| endswith "chem" ac	= Just $ Sub Chemistry
												| endswith "phys" ac	= Just $ Sub Physics
												| endswith "math" ac	= Just $ Sub Math
												| endswith "energy" ac	= Just $ Sub Energy
												| endswith "gen" ac		= Just $ Sub Energy
												| endswith "earth" ac	= Just $ Sub ERSP
												| endswith "ersp" ac	= Just $ Sub ERSP
												| endswith "ersc" ac	= Just $ Sub ERSP
												| endswith "astro" ac	= Just $ Sub ERSP
												| otherwise				= Nothing
									
									parseatype									= if (samesym ls (Sub Biology)) then parseatype' else Nothing
									parseatype'	| endswith "short answer" ac	= Just $ Typ ShortAnswer
												| endswith "multiple choice" ac	= Just $ Typ MultipleChoice
												| otherwise						= Nothing
									
									parsetext	| (samesym ls (Typ ShortAnswer)) && (s /!: sep)	= Just $ Text ""
												| (samesym ls (Text ""))						= Just $ Text acc
												| otherwise										= Nothing
									
									isJust (Just x) = x
									isJust Nothing 	= error "Can't take isJust of Nothing"
									
									nill Nothing 	= True
									nill (Just x)	= False
									
									samesym None None = True
									samesym (State _) (State _) = True
									samesym (Sub _) (Sub _) = True
									samesym (Typ _) (Typ _) = True
									samesym (Text _) (Text _) = True
									samesym (W _) (W _) = True
									samesym (X _) (X _) = True
									samesym (Y _) (Y _) = True
									samesym (Z _) (Z _) = True
									samesym (Ans _) (Ans _) = True
									samesym _ _ = False --}
	
	splitWhen p s =	case dropWhile p s of
						[] -> []
						s' -> w : splitWhen p s''
							where
								(w, s'') = break p s'
	
	nicesplitWhen ac p [] = [ac]
	nicesplitWhen ac p (s:rest)	| p s	= (ac ++ [s]) : (nicesplitWhen [] p rest)
								| True	= nicesplitWhen (ac ++ [s]) p rest
	
	eagersplitWhen ac p [] = [ac]
	eagersplitWhen ac p (s:rest)	| p s	= ac : (eagersplitWhen [s] p rest)
									| True	= eagersplitWhen (ac ++ [s]) p rest
			
	ischoicer str = ((count True . map (!!: ['w' .. 'z']) $ str) <= 1) && (and . map (!!: (sep ++ ['w' .. 'z'])) $ str)
	
	factor xs MultipleChoice = map (replace "\n" " ") $ if (length splitted > 4) then ([body, choiceW, choiceX, choiceY, choiceZ, ans]) else (factor xs ShortAnswer)
		where
			reveal = map (\x -> (ischoicer . take 3 . map toLower $ x, x)) xs
			splitted = eagersplitWhen [] (\(c, x) -> c) reveal
			body = concat . map snd $ splitted !! 0
			choiceW = concat . map snd $ splitted !! 1
			choiceX = concat . map snd $ splitted !! 2
			choiceY = concat . map snd $ splitted !! 3
			choiceZ = concat . init . map snd $ last splitted
			ans = last . map snd $ last splitted
			--choiceZ = concat . head . eagersplitWhen [] (\x -> startswith "answer:" $ map toLower x) . map snd $ last splitted
			--ans = concat . last . eagersplitWhen [] (\x -> startswith "answer:" $ map toLower x) . map snd $ last splitted
	
	factor (x:y:[]) ShortAnswer = x:y:[]
	factor (x:y:zs) ShortAnswer = factor ((x ++ "\n" ++ y):zs) ShortAnswer
	factor xs ShortAnswer = []
	{--factor (bod:w:x:y:z:ans:[]) MultipleChoice = bod:w:x:y:z:ans:[]
	factor (aaa:b:c:d:e:fff:gs) MultipleChoice = factor ((aaa ++ "\n" ++ b):c:d:e:fff:gs) MultipleChoice
	factor xs MultipleChoice = factor xs ShortAnswer--}
	
	questions doclines = if (hastossup doclines) then (qsplittossupbonus doclines) else (if (hasanswer doclines) then (qsplitanswer doclines) else (qsplitnum [] 1 doclines))
		where
			
			hastossup = or . map (startswith "toss" . map toLower)
			
			hasanswer = or . map (startswith "ans" . map toLower)
		
			qsplittossup = splitWhen (\line -> startswith "toss" (map toLower line))
			
			qsplitbonus = splitWhen (\line -> startswith "toss" (map toLower line))
			
			qsplittossupbonus = splitWhen (\line -> (startswith "toss" (map toLower line)) || (startswith "bonus" (map toLower line)))
			
			qsplitanswer = nicesplitWhen [] (\line -> startswith "ans" (map toLower line))
			
			qsplitnum ac n [] = []
			qsplitnum ac n (l:lines) = if ((startswith (show n) l) || (startswith (show n) (tail l))) then (ac : (qsplitnum [l] (n + 1) lines)) else (qsplitnum (ac ++ [l]) n lines)
	
	hasbonus = or . map (startswith "bonus" . map toLower)
	
	doubleup [] = []
	doubleup (x:[]) = []
	doubleup (x:y:zs) = if ((getsubject <$> x) == (getsubject <$> y)) then ((Double <$> x <*> y) : (doubleup zs)) else (doubleup (y:zs))
	
	singleup xs = map (\x -> Single <$> x) xs
	
	questionup double xs = if double then (doubleup xs) else (singleup xs)
	
	parsequestion [] = Nothing
	parsequestion (h:question) = if (realquestion == Just []) then Nothing else ((,,) <$> realheadparse <*> realbodyparse <*> realanswerparse)
		where
			answertype = (\(x, y, z) -> y) <$> headparse
			headparse = parsehead h
			oldheadparse = headparse
			realquestion = factor (h:question) <$> answertype
			realheadparse = realquestion >>= parsehead . head
			oldbodyparse = parsebody answertype $ init question
			realbodyparse = realquestion >>= parsebody answertype . init . tail
			oldanswerparse = parseanswer $ last question
			realanswerparse =  realquestion >>= parseanswer . last
	
	toquestion tag Nothing = Nothing
	toquestion tag (Just ((sub, MultipleChoice, text), choices, ans)) = Just $ Multiple tag sub text choices ans
	toquestion tag (Just ((sub, ShortAnswer, text), body, ans)) = Just $ Short tag sub gettext ans
		where
			gettext = text ++ "\n\n" ++ (intercalate "\n\n" body)
	
	trytake n xs 	| n > (length xs)	= xs
					| otherwise			= take n xs
	
	parsehead :: String -> Maybe (Subject, AType, String)
	parsehead line = lsub [] (trytake 60 . afternum $ line) (afternum line)
		where
			afternum = dropWhile (/!: alpha)
			
			lsub ac []		 rs									= ltype [] Nothing ac ac
			lsub ac (l:line) (r:rs)	| (endswith "bio" ac)		= ltype [] (Just Biology) line rs
									| (endswith "chem" ac)		= ltype [] (Just Chemistry) line rs
									| (endswith "phys" ac)		= ltype [] (Just Physics) line rs
									| (endswith "math" ac)		= ltype [] (Just Math) line rs
									| (endswith "energy" ac)	= ltype [] (Just Energy) line rs
									| (endswith "gen" ac)		= ltype [] (Just Energy) line rs
									| (endswith "earth" ac)		= ltype [] (Just ERSP) line rs
									| (endswith "ersp" ac)		= ltype [] (Just ERSP) line rs
									| (endswith "ersc" ac)		= ltype [] (Just ERSP) line rs
									| (endswith "astro" ac)		= ltype [] (Just ERSP) line rs
									| otherwise					= lsub  (ac ++ [toLower l]) line rs
			
			ltype ac sub []       rs		| (endswith "short answer" ac)		= (,,) <$> sub <*> (Just ShortAnswer)    <*> (lquestion rs)
											| (endswith "multiple choice" ac)	= (,,) <$> sub <*> (Just MultipleChoice) <*> (lquestion rs)
											| otherwise							= (,,) <$> sub <*> Nothing               <*> (lquestion rs)
			ltype ac sub (l:line) (r:rs)	| (endswith "short answer" ac)		= (,,) <$> sub <*> (Just ShortAnswer)    <*> (lquestion rs)
											| (endswith "multiple choice" ac)	= (,,) <$> sub <*> (Just MultipleChoice) <*> (lquestion rs)
											| otherwise							= ltype (ac ++ [toLower l]) sub line rs
			
			lquestion = Just . dropWhile (/!: (alpha ++ num))
	
	parsebody :: Maybe AType -> [String] -> Maybe [String]
	parsebody Nothing body = Nothing
	parsebody (Just ShortAnswer) body = Just body
	parsebody (Just MultipleChoice) body = if (length body >= 4) then (parsechoices $ take 4 body) else Nothing
	
	parsechoices :: [String] -> Maybe [String]
	parsechoices lines = foldr (\a b -> (:) <$> a <*> b ) (Just []) $ map (lchoice "") lines
		where
			lchoice ac [] = Nothing
			lchoice ac (l:line) = if (ischoicer (ac ++ [toLower l])) then (lchoice (ac ++ [toLower l]) line) else (Just $ l:line)
			
			ischoicer str = ((count True . map (!!: ['w' .. 'z']) $ str) <= 1) && (and . map (!!: (sep ++ ['w' .. 'z'])) $ str)
	
	{-- TODO: implement a smarter choice parser
	smartbodyparse :: [String] -> Maybe ([String], String)
	smartbodyparse lines = lchoice "" . concat . intersperse "\n" $ lines
		where
			lchoice ac [] = Nothing
			lchoice ac (l:line) = if (ischoicer (ac ++ [toLower l])) then (
			
			ischoicer str = ((count True . map (!!: ['w' .. 'z']) $ str) <= 1) && (and . map (!!: (sep ++ ['w' .. 'z'])) $ str)
			
			isanswer str = startswith "answer" . map (toLower) . dropWhile (!!: sep) $ str --}
	
	count b [] = 0
	count b (a:as)	| b == a = 1+(count b as)
					| otherwise = count b as
	
	parseanswer line = Just $ if (startswith "answer" $ map (toLower) trimmed) then (dropWhile (!!: sep) $ drop 6 trimmed) else trimmed
		where
			trimmed = dropWhile (!!: sep) line
	
	(!!:) = elem
	(/!:) xs = not . (!!:) xs
	alpha = ['a' .. 'z'] ++ ['A' .. 'Z']
	num = ['0' .. '9']
	sep = ['(', ')', ':', ';', '-', ' ', '.', '\n', '#', '*']
