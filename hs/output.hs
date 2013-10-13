-- Written by Siddharth Trehan

module Main where
	
	import Prelude hiding (getContents, print, filter, map)
	import Round
	--import System.IO.UTF8
	import Data.ByteString.Lazy
	import Text.Show.ByteString
	
	main = do
				contents <- getContents
				let round = getround "Rohan Deshpande" contents
				print round
	
	getround authors contents = Round qs
		where
			qs = dropMaybe . questionup (hasbonus . lines $ contents) . map (toquestion (Tag authors) . parsequestion) . questions . lines $ contents
	
	dropMaybe = map isJust . filter (/= Nothing)
		where
			isJust (Just x) = x
			isJust Nothing 	= error "Cannot take isJust of Nothing"

