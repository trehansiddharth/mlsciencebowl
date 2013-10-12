{-# LANGUAGE TupleSections #-}

data Ring a = Ring a [a] -- current position, list of clockwise partners
				deriving (Show, Eq, Read)
data State a = Alive a | Dead a
				deriving (Show, Eq, Read)

next (Ring x (y:ys)) = Ring y (ys ++ [x])
previous (Ring x ys) = Ring (last ys) (x:(init ys))

alive (Alive x) = True
alive (Dead x) = False

seek (Ring i (x:xs)) = x

switch ring = if (alive . seek $ ring) then (nextalive . kill . next $ ring) else (switch . next $ ring)

kill (Ring (Alive i) xs) = Ring (Dead i) xs

nextalive ring = if (alive . seek $ ring) then (next ring) else (nextalive . next $ ring)

-- CHALLENGE: rewrite this code using state transformers and comonads
