module JsonDecoder exposing (extractTabs, decodeData, BackendData)

import Json.Decode as Json exposing ((:=))
import Tabs exposing (Tab)


type alias BackendData =
    { lang : Maybe String
    , tabs : Maybe (List Tab)
    , content : String
    }


extractTabs : Json.Value -> List Tab
extractTabs tabs =
    {--let
        one =
            Debug.log "flags" tabs
    in --}
    case Json.decodeValue decodeTabs tabs of
        Err msg ->
            let
                two =
                    Debug.log "err" msg

                default =
                    Tabs.defaultTab
            in
                [ { default | title = "error" } ]

        Ok tabs ->
            tabs


decodeTabs : Json.Decoder (List Tab)
decodeTabs =
    Json.at [] (Json.list decodeTab)


decodeTab : Json.Decoder Tab
decodeTab =
    Json.object3 Tab
        ("url" := Json.string)
        ("parent" := Json.string)
        ("title" := Json.string)


decodeInit : Json.Decoder String
decodeInit =
    Json.at [ "lang" ] Json.string


decodeData : Json.Decoder BackendData
decodeData =
    Json.object3 BackendData
        (Json.maybe ("lang" := Json.string))
        (Json.maybe ("tabs" := decodeTabs))
        ("content" := Json.string)
