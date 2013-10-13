-- Written by Siddharth Trehan

module Main where
	
	import Prelude
	import Round
	import qualified System.IO.UTF8 as IO
	--import System.IO as IO
	import qualified Text.Show.ByteString as BShow
	import qualified Data.ByteString.Char8 as B
	import Database.HDBC
	import Database.HDBC.MySQL
	import Control.Monad
	import System.Environment
	
	main = do
		args <- getArgs
		let input = args !! 0
		let rid = args !! 1
		contents <- IO.readFile input
		let qlist = (\(Round x) -> x) . getround $ contents
		rows <- withRTSSignalsBlocked $ do
			conn <- connectMySQL defaultMySQLConnectInfo {
				mysqlHost     = "localhost",
				mysqlUser     = "mlsciencebowl",
				mysqlPassword = "planck",
				mysqlDatabase = "mlsciencebowl",
				mysqlPort     = 3306,
				mysqlUnixSocket = "/var/run/mysqld/mysqld.sock"
			}
			forM_ qlist $ \question -> dbquestion conn question rid
			commit conn
		return ()
		--return ()
				--contents <- getContents
				--let round = getround "Rohan Deshpande" contents
				--print round
	
	dbquestion conn (Single ask) rid = do
		qid <- dbask conn ask
		mod <- run conn ("INSERT INTO tempquestions VALUES(" ++ qid ++ ", NULL, '" ++ (show . getsubject $ ask) ++ "', '" ++ rid ++ "')") []
		putStrLn qid
	
	dbquestion conn (Double ask1 ask2) rid = do
		qid1 <- dbask conn ask1
		qid2 <- dbask conn ask2
		mod <- run conn ("INSERT INTO tempquestions VALUES(" ++ qid1 ++ ", " ++ qid2 ++ ", '" ++ (show . getsubject $ ask1) ++ "', '" ++ rid ++ "')") []
		putStrLn $ "(" ++ qid1 ++ ", " ++ qid2 ++ ")"
	
	dbask conn (Short subject text answer) = do
		run conn ("INSERT INTO qbank VALUES(DEFAULT, ?, NULL, NULL, NULL, NULL, ?)") [toSql $ B.pack text, toSql $ B.pack answer]
		result <- quickQuery' conn "SELECT LAST_INSERT_ID()" []
		return . show . (\(SqlWord64 x) -> x) . head . head $ result
	
	dbask conn (Multiple subject text (choicew:choicex:choicey:choicez:[]) answer) = do
		run conn ("INSERT INTO qbank VALUES(DEFAULT, ?, ?, ?, ?, ?, ?)") [toSql $ B.pack text, toSql choicew, toSql $ B.pack choicex, toSql $ B.pack choicey, toSql $ B.pack choicez, toSql $ B.pack answer]
		result <- quickQuery' conn "SELECT LAST_INSERT_ID()" []
		return . show . (\(SqlWord64 x) -> x) . head . head $ result
	
	getround contents = Round qs
		where
			qs = dropMaybe . questionup (hasbonus . lines $ contents) . map (toquestion . parsequestion) . questions . lines $ contents
	
	dropMaybe = map isJust . filter (/= Nothing)
		where
			isJust (Just x) = x
			isJust Nothing 	= error "Cannot take isJust of Nothing"
	
	getq (Round ((Double q1 (Short subj text ans)):rest)) = text

