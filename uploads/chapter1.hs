import System.Random
import Control.Applicative

randomfloat = getStdRandom (randomR (0.0, 1.0))

randompoint = (,) <$> randomfloat <*> randomfloat

runM 
