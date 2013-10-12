{-# LANGUAGE MultiParamTypeClasses #-}
{-# LANGUAGE ExistentialQuantification #-}
{-# LANGUAGE GADTs #-}
{-# LANGUAGE DatatypeContexts #-}
{-# LANGUAGE FlexibleInstances #-}
{-# LANGUAGE TypeFamilies #-}
{-# LANGUAGE FlexibleContexts #-}

module Server where

	import System.IO
	import Network.HaskellNet.IMAP
	import Data.ByteString.Char8
	import Control.Monad
	import Network.HaskellNet.IMAP.SSL
	import Codec.MIME.Parse
	import Control.Applicative
	import Codec.MIME.Type
	import Network.HaskellNet.IMAP.Connection
	import Codec.Crypto.RSA
	
	class LockState a
	data LOpen = Open
	data LClosed = Closed
	instance LockState LOpen
	instance LockState LClosed
	
	class (LockState a, LockState b) => Locks a b where
		type LMerge a b :: *
	instance Locks LOpen LOpen where
		type LMerge LOpen LOpen = LOpen
	instance Locks LOpen LClosed where
		type LMerge LOpen LClosed = LClosed
	instance Locks LClosed LOpen where
		type LMerge LClosed LOpen = LClosed
	instance Locks LClosed LClosed where
		type LMerge LClosed LClosed = LClosed
	
	class Server server where
		type Key server :: *
		type Query server :: *
		lookup :: SHandle h => server -> Query server -> [h]
		open :: Key server -> serve -> IO server
		close :: server l' -> IO server
		pin :: String -> server -> IO s
		glance :: server -> IO [String]
	
	class SHandle h where
		transfer :: Server s => h -> s -> IO h
		uid :: h -> Int
		name :: h -> String
		split :: h -> IO (h, h)
		author :: h -> String
		size :: h -> IO Int
		shGetBody :: h -> IO String
		shGetContents :: h -> IO String
		shPutStr :: String -> h -> IO ()
		properties :: h -> IO [(String, a)]
		set :: a -> b -> h -> IO h
	
	--data  Source s q k => Only s q k = Only s
	--data Pool q k = forall s. Source s q k => Pool [Only s q k]
	
	
	class DList a
	data (Server s) => DOnly s
	data (DList a) => DMerge x a
	instance DList (DOnly s)
	instance DList (DMerge x a)
	data Pool k q a where
		Only :: s -> Pool (Key s) (Query s) (DOnly s)
		Merge :: (DList a) => s -> Pool (Key s) (Query s) a -> Pool (Key s) (Query s) (DMerge (DOnly s) a)
	
	instance Server (Pool k q a) where
		type Key (Pool k q a) = k
		type Query (Pool k q a) = q
		close (Only s) = Only <$> Server.close s
		close (Merge s p) = Merge <$> Server.close s <*> Server.close p
	
	{--
	class Server s => Tryopen s l where
		type Willopen s :: *
		tryopen :: LockState l => Key server -> server l -> IO (server l')
	
	instance Tryopen s LOpen where
		type Willopen s :: LOpen
		tryopen --}
	
	--instance Source (Pool q k) q k where
	--	sempty = return $ Pool []
		
	--	open (Pool sources) key = undefined
	
	{--instance Source s q k => Source (Only s q k) q k where
		sempty = undefined
		
		open (Only source) key = open' source key
			where
				open' :: Source s q k => s -> k -> IO (Only s q k)
				open' source' key' = Only <$> open source' key'--}
	
	data Gmail l = LockState l => Gmail l
	data Disk l = LockState l => Disk l
	data UserPass = UserPass String String
	
	instance Server Gmail where
		type Key Gmail = UserPass
		type Query Gmail = Gmail LOpen
	instance Server Disk where
		type Key Disk = UserPass
		type Query Disk = Gmail LOpen
