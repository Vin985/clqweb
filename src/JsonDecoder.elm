module JsonDecoder exposing (extractTabs, Tab, defaultTab)

import Json.Decode exposing (..)


type alias Tab =
    { current : Bool
    , url : String
    , parent : String
    , title : String
    }


defaultTab : Tab
defaultTab =
    { current = False
    , url = ""
    , parent = ""
    , title = "prout"
    }


extractTabs : Json.Decode.Value -> List Tab
extractTabs tabs =
    {--let
        one =
            Debug.log "flags" tabs
    in --}
    case decodeValue decodeTabs tabs of
        Err msg ->
            let
                two =
                    Debug.log "err" msg
            in
                [ { defaultTab | title = "error" } ]

        Ok tabs ->
            tabs


decodeTabs : Decoder (List Tab)
decodeTabs =
    at [] (list decodeTab)


decodeTab : Decoder Tab
decodeTab =
    Json.Decode.object4 Tab
        ("current" := bool)
        ("url" := string)
        ("parent" := string)
        ("title" := string)
