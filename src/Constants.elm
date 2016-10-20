module Constants exposing (..)

import Dict exposing (Dict)


constants =
    { email = "info@campinglequebecois.qc.ca"
    , phone = "(450) 788-2680"
    }


langsText : Dict String String
langsText =
    Dict.fromList <| [ ( "fr", "Francais" ), ( "en", "English" ) ]
