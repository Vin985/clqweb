module JsonDecoder exposing (extractTabs, decodeData)

import Json.Decode as Json exposing (field)
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
    Json.map3 Tab
        (field "url" Json.string)
        (field "title" Json.string)
        (Json.maybe <| field "parent" Json.string)


decodeLanguages : Json.Decoder Languages
decodeLanguages =
    Json.map initLanguages <| Json.list Json.string


decodePage : Json.Decoder Page
decodePage =
    Json.map3 Page
        (field "title" Json.string)
        (field "content" Json.string)
        (Json.maybe <| field "children" decodeTabs)


decodeData : Json.Decoder BackendData
decodeData =
    Json.map3 BackendData
        (Json.maybe <| field "langs" decodeLanguages)
        (Json.maybe <| field "tabs" decodeTabs)
        (field "page" decodePage)
