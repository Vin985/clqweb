{- This file re-implements the Elm Counter example (1 counter) with elm-mdl
   buttons. Use this as a starting point for using elm-mdl components in your own
   app.
-}


port module Main exposing (..)

import Html.App as App
import Html exposing (..)
import Html.Attributes exposing (href, class, style, src)
import Material
import Material.Options exposing (css)
import Material.Layout as Layout
import Material.Color as Color
import Material.Menu as Menu
import Material.Icon as Icon
import Material.Options as Options exposing (css, when, cs)
import Json.Decode as Json exposing (Value)
import JsonDecoder exposing (extractTabs, Tab, defaultTab, BackendData, decodeData)
import Http
import Task
import Constants exposing (constants)


-- MODEL
-- You have to add a field to your model where you track the `Material.Model`.
-- This is referred to as the "model container"


type alias Model =
    { count : Int
    , mdl : Material.Model
    , selectedTab : Int
    , tabs : List Tab
    , siteurl : String
    , lang : String
    , content : String
    }



-- `Material.model` provides the initial model


model : Model
model =
    { count = 0
    , mdl =
        Material.model
        -- Boilerplate: Always use this initial Mdl model store.
    , selectedTab = 0
    , tabs = [ defaultTab ]
    , siteurl = ""
    , lang = "fr"
    , content = "Nothing"
    }



-- ACTION, UPDATE
-- You need to tag `Msg` that are coming from `Mdl` so you can dispatch them
-- appropriately.


type Msg
    = Mdl (Material.Msg Msg)
    | SelectTab Int
    | Nop
    | NavTabs Json.Value
    | FetchFail Http.Error
    | FetchSucceed BackendData


getData : String -> String -> Json.Decoder BackendData -> Cmd Msg
getData siteurl page decoder =
    let
        url =
            Http.url (siteurl ++ "index.php") [ ( "id", "fetchdata" ), ( "page", page ) ]
    in
        Task.perform FetchFail FetchSucceed (Http.get decoder url)


getInit : String -> Cmd Msg
getInit siteurl =
    getData siteurl "init" decodeData



{- getInit : String -> String -> Decoder -> Cmd Msg -}
-- Boilerplate: Msg clause for internal Mdl messages.


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        -- When the `Mdl` messages come through, update appropriately.
        Mdl msg' ->
            Material.update msg' model

        SelectTab num ->
            { model | selectedTab = num } ! []

        Nop ->
            ( model, Cmd.none )

        NavTabs value ->
            ( model, Cmd.none )

        FetchSucceed data ->
            let
                lang' =
                    case data.lang of
                        Nothing ->
                            "fr"

                        Just lang ->
                            lang

                tabs' =
                    case data.tabs of
                        Nothing ->
                            []

                        Just tabs ->
                            tabs

                one =
                    Debug.log "lang" data.lang

                two =
                    Debug.log "siteurl" data.tabs
            in
                ( { model | lang = lang', tabs = tabs', content = data.content }, Cmd.none )

        FetchFail _ ->
            ( model, Cmd.none )



-- VIEW


type alias Mdl =
    Material.Model


view : Model -> Html Msg
view model =
    Layout.render Mdl
        model.mdl
        [ Layout.fixedHeader
        , Layout.selectedTab model.selectedTab
        , Layout.onSelectTab SelectTab
        , Options.set (\config -> { config | rippleTabs = False })
        ]
        { header = header model
        , drawer = []
        , tabs =
            if not (model.mdl.layout.tabScrollState.canScrollRight || model.mdl.layout.tabScrollState.canScrollLeft) then
                ( List.map (\x -> text x.title) model.tabs
                , [ css "justify-content" "flex-end"
                  , css "font-weight" "bold"
                  , cs "no_scroll"
                  , Color.background (Color.color Color.LightGreen Color.S300)
                  ]
                )
            else
                ( [], [] )
        , main = [ viewBody model ]
        }


header : Model -> List (Html Msg)
header model =
    [ Layout.row
        [ css "height" "120px"
        , Color.background (Color.color Color.LightBlue Color.S50)
        ]
        [ Layout.title [] [ img [ src "theme/clq/css/img/logo-simple.png" ] [] ]
        , Layout.spacer
        , Layout.navigation [] (viewNavlinks model)
          {- [ Layout.link [ Layout.onClick Nop ]
                 [ Icon.i "phone"
                 , text constants.email
                 ]
             , Layout.link [ Layout.href "mailto:info@campinglequebecois.qc.ca" ]
                 [ Icon.i "email"
                 , text constants.phone
                 ]
             , Layout.link [ Layout.href (Http.url model.siteurl [ ( "setlang", model.lang ) ]) ]
                 [ text "Francais" ]
             ]
          -}
        ]
    ]


viewBody : Model -> Html Msg
viewBody model =
    case model.selectedTab of
        0 ->
            text model.content

        1 ->
            text "something else"

        _ ->
            text "404"


viewNavlinks : Model -> List (Html Msg)
viewNavlinks model =
    List.map viewNavlink model.tabs
        ++ [ Menu.render Mdl
                [ 0 ]
                model.mdl
                [ Menu.bottomRight ]
                [ Menu.item [ Menu.onSelect Nop ]
                    [ Icon.i "phone"
                    , text constants.phone
                    ]
                , Menu.item [ Menu.onSelect Nop ]
                    [ Icon.i "email"
                    , text constants.email
                    ]
                ]
           ]


viewNavlink : Tab -> Html Msg
viewNavlink tab =
    Layout.link [ Layout.onClick Nop ]
        [ text tab.title
        ]


type alias Flags =
    { tabs : Json.Value
    , siteurl : String
    }


init : Flags -> ( Model, Cmd Msg )
init flags =
    ( { model
        | mdl = Layout.setTabsWidth 800 model.mdl {- , tabs = extractTabs flags.tabs -}
        , siteurl = flags.siteurl
      }
    , Cmd.batch [ Material.init Mdl, getInit flags.siteurl ]
    )


main : Program Flags
main =
    App.programWithFlags
        { init = init
        , view = view
        , subscriptions = Material.subscriptions Mdl
        , update = update
        }
