{-# LANGUAGE MultiParamTypeClasses #-}
{-# LANGUAGE ExistentialQuantification #-}
{-# LANGUAGE GADTs #-}
{-# LANGUAGE FlexibleInstances #-}
{-# LANGUAGE TypeFamilies #-}
{-# LANGUAGE FlexibleContexts #-}
{-# LANGUAGE UndecidableInstances #-}
{-# LANGUAGE PolyKinds #-}
{-# LANGUAGE DataKinds #-}

module Server where

	import System.IO
	import Control.Monad
	import Control.Applicative
	import Data.List
	import Control.Monad.Trans.Maybe
	import Control.Monad
	import GHC.Word
	
	-- "Server" class for all server objects (Gmail, Disk, etc.)
		
	class (SHandle (HandleFor server), Show (HandleFor server)) => Server server where
		type KeyFor server :: *
		type QueryFor server :: *
		type HandleFor server :: *
		type ActionFor server :: *
		type ResultFor server :: *
		lookup :: (SHandle (HandleFor server)) => [QueryFor server] -> [ActionFor server] -> server -> MaybeT IO [HandleFor server]
		open :: KeyFor server -> server -> MaybeT IO server
		close :: server -> IO ()
		actions :: server -> [ActionFor server]
	
	-- "Wire" class deals with handle interactions between servers
	
	class (Server s1, Server s2) => Wire s1 s2 where
		transfer :: (SHandle (HandleFor s1), SHandle (HandleFor s2)) => (HandleFor s1) -> s -> IO (HandleFor s2)
		trace :: (SHandle (HandleFor s1), SHandle (HandleFor s2)) => (HandleFor s1) -> s -> MaybeT IO (HandleFor s2)
	
	-- "SHandle" class used to handle files on different servers
	
	class SHandle h where
		type HActionFor h :: *
		type HResultFor h :: *
		uid :: h -> Word64 -- this is a hack, MUST be fixed
		name :: h -> String
		author :: h -> String
		split :: h -> IO (h, h)
		withHandle :: (Handle -> IO a) -> h -> IO a
		properties :: h -> IO [(String, String)]
		set :: String -> a -> h -> IO h
		command :: String -> h -> IO h
		perform :: (HActionFor h) -> h -> MaybeT IO (HResultFor h)
	
	class Similar a b where
		similar :: a -> b -> Bool
	
	instance (SHandle a, SHandle b) => Similar a b where
		similar h1 h2 = (uid h1) == (uid h2)
	
	-- Abstract from sources to pools
	
	data Source k q a r where
		Source :: forall s. (Server s, Show s) => s -> Source (KeyFor s) (QueryFor s) (ActionFor s) (ResultFor s)
	
	data SourceHandle ha r where
		SourceHandle :: forall h. (SHandle h, Show h) => h -> SourceHandle (HActionFor h) (HResultFor h)
	
	instance SHandle (SourceHandle ha r) where
		uid (SourceHandle h) = uid h
		name (SourceHandle h) = name h
		author (SourceHandle h) = author h
		split (SourceHandle h) = (\(h1, h2) -> (SourceHandle h1, SourceHandle h2)) <$> split h
		withHandle f (SourceHandle h) = withHandle f h
		properties (SourceHandle h) = properties h
		set prop value (SourceHandle h) = SourceHandle <$> set prop value h
		command cmd (SourceHandle h) = SourceHandle <$> command cmd h
	
	instance Show (SourceHandle ha r) where
		show (SourceHandle h) = "SourceHandle " ++ (show h)
	
	instance Show (Source k q a r) where
		show (Source s) = "Source " ++ (show s)
	
	--Both Source and Pool are instances of Server
	
	instance Server (Source k q a r) where
		type KeyFor (Source k q a r) = [k]
		type QueryFor (Source k q a r) = q
		type HandleFor (Source k q a r) = SourceHandle a r
		type ActionFor (Source k q a r) = a
		type ResultFor (Source k q a r) = r
		
		open [] (Source s) = MaybeT $ return Nothing
		open (k:keys) (Source s) = (Source <$> open k s) `mplus` (open keys (Source s))
		
		close (Source s) = Server.close s
		
		actions (Source s) = actions s
		
		lookup queries actions (Source s) = map SourceHandle <$> Server.lookup queries actions s
	
	instance Server [Source k q a r] where
		type KeyFor [Source k q a r] = [k]
		type QueryFor [Source k q a r] = q
		type HandleFor [Source k q a r] = SourceHandle a r
		type ActionFor [Source k q a r] = a
		type ResultFor [Source k q a r] = r
		
		-- all or none behavior when using keys (can these be rewritten more elegantly?)
		
		open key [] = return []
		open key (s:ss) = (:) <$> open key s <*> open key ss
		
		close ss = forM_ ss Server.close
		
		lookup queries actions [] = return []
		lookup q a pool = nubb <$> map SourceHandle <$> lookup' q a pool
			where
				lookup' queries actions [] = return []
				lookup' queries actions (s:ss) = (++) <$> Server.lookup queries actions s <*> lookup' queries actions ss
		
		actions pool = undefined --TODO
	
	nubb [] = []
	nubb (x:xs) = iff (present x xs) (nubb xs) (nubb (x:xs))
	
	present a [] = False
	present (SourceHandle h) ((SourceHandle h') : shs) = (similar h h') || (present (SourceHandle h) shs)
	
	iff True x y = x
	iff False x y = y
