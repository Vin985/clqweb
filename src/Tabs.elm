module Tabs exposing (Model, model, Tab, view, defaultTab, Msg, update)

import Material.Icon as Icon
import Material.Options exposing (css)
import Material.Menu as Menu
import Material
import Html exposing (..)
import Html exposing (..)
import Material.Layout as Layout
import Material.Options as Options exposing (css, when, cs, nop)
import String exposing (..)
import Constants exposing (constants)


type alias Model =
    { current : String
    , tabs : List Tab
    , mdl : Material.Model
    }


model : List Tab -> Model
model tabs' =
    { current = "news"
    , tabs = tabs'
    , mdl = Material.model
    }


type alias Tab =
    { url : String
    , parent : String
    , title : String
    }


defaultTab : Tab
defaultTab =
    { url = ""
    , parent = ""
    , title = "prout"
    }


type Msg
    = Mdl (Material.Msg Msg)
    | Nop
    | ChangePage String


update : Msg -> Model -> ( Model, Cmd Msg, String )
update msg model =
    case msg of
        -- When the `Mdl` messages come through, update appropriately.
        Mdl msg' ->
            let
                ( updated, cmd ) =
                    Material.update msg' model
            in
                ( updated, cmd, "" )

        Nop ->
            ( model, Cmd.none, "" )

        ChangePage url ->
            ( { model | current = url }, Cmd.none, url )


view : Model -> List (Html Msg)
view model =
    let
        i name =
            Icon.view name [ css "width" "40px" ]

        padding =
            css "padding-right" "24px"
    in
        List.map (viewTab model.current) model.tabs
            ++ [ Menu.render Mdl
                    [ 0 ]
                    model.mdl
                    [ Menu.bottomRight, Menu.icon "contact_phone" ]
                    [ Menu.item [ Menu.onSelect Nop, padding ]
                        [ i "phone"
                        , text constants.phone
                        ]
                    , Menu.item [ Menu.onSelect Nop, padding ]
                        [ i "email"
                        , text constants.email
                        ]
                    ]
               ]


viewTab : String -> Tab -> Html Msg
viewTab current tab =
    let
        fontStyle =
            [ cs "mdl-layout__tab"
            , css "font-weight" "500"
            , css "padding" "0 20px"
            , css "line-height" "48px"
            , css "outline" "none"
            , css "cursor" "default"
            ]
    in
        Layout.link
            ([ Layout.onClick (ChangePage tab.url), when (cs "is-active") (tab.url == current) ]
                ++ fontStyle
            )
            [ text (toUpper tab.title)
            ]
