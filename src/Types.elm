module Types exposing (Tabs, Tab, defaultTab, BackendData, initLanguages, Languages, Page, defaultPage)


type alias Tabs =
    List Tab


type alias Languages =
    { current : String
    , other : List String
    }


initLanguages : List String -> Languages
initLanguages languages =
    case languages of
        [] ->
            Languages "fr" []

        first :: others ->
            Languages first others


type alias Tab =
    { url : String
    , title : String
    , parent : Maybe String
    }


type alias Page =
    { title : String
    , content : String
    , children : Maybe Tabs
    }


defaultPage : Page
defaultPage =
    { title = ""
    , content = ""
    , children = Nothing
    }


type alias BackendData =
    { lang : Maybe Languages
    , tabs : Maybe (List Tab)
    , page : Page
    }


defaultTab : Tab
defaultTab =
    { url = ""
    , title = ""
    , parent = Just ""
    }
