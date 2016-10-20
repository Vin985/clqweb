module JsonDecoder exposing (extractTabs, decodeData)

import Json.Decode as Json exposing ((:=))
import Types exposing (Tab, BackendData, initLanguages, Languages, Page)


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
                    Types.defaultTab
            in
                [ { default | title = "error" } ]

        Ok tabs ->
            tabs


decodeTabs : Json.Decoder (List Tab)
decodeTabs =
    Json.at [] (Json.list decodeTab)


decodeTab : Json.Decoder Tab
decodeTab =
    Json.object2 Tab
        ("url" := Json.string)
        ("title" := Json.string)


decodeLanguages : Json.Decoder Languages
decodeLanguages =
    Json.object1 initLanguages <| Json.list Json.string


decodePage : Json.Decoder Page
decodePage =
    Json.object3 Page
        ("title" := Json.string)
        ("content" := Json.string)
        (Json.maybe <| "children" := decodeTabs)


decodeData : Json.Decoder BackendData
decodeData =
    Json.object3 BackendData
        (Json.maybe <| "langs" := decodeLanguages)
        (Json.maybe <| "tabs" := decodeTabs)
        ("page" := decodePage)
