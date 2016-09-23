{- This file re-implements the Elm Counter example (1 counter) with elm-mdl
   buttons. Use this as a starting point for using elm-mdl components in your own
   app.
-}


port module Main exposing (..)

import Html.App as App
import Html exposing (..)
import Html.Lazy
import Html.Attributes exposing (href, class, style, src)
import Material
import Material.Options exposing (css)
import Material.Grid exposing (..)
import Material.Layout as Layout
import Material.Color as Color
import Material.Options as Options exposing (css, when, cs)
import Json.Decode as Json exposing (Value)
import JsonDecoder exposing (extractTabs, BackendData, decodeData)
import Http
import Task
import Tabs exposing (Tab)


-- MODEL
-- You have to add a field to your model where you track the `Material.Model`.
-- This is referred to as the "model container"


type alias Page =
    { title : String
    , url : String
    , content : String
    }


type alias Languages =
    { currentLang : String
    , otherLang : List String
    }


type alias Model =
    { mdl : Material.Model
    , tabs : Tabs.Model
    , siteurl : String
    , lang : String
    , content : String
    , current : String
    }



-- `Material.model` provides the initial model


model : Model
model =
    { mdl =
        Material.model
        -- Boilerplate: Always use this initial Mdl model store.
    , tabs = Tabs.model []
    , siteurl = ""
    , lang = "fr"
    , content = "Nothing"
    , current = ""
    }



-- ACTION, UPDATE
-- You need to tag `Msg` that are coming from `Mdl` so you can dispatch them
-- appropriately.


type Msg
    = Mdl (Material.Msg Msg)
    | Nop
    | NavTabs Json.Value
    | FetchFail Http.Error
    | FetchSucceed BackendData
    | TabMsg Tabs.Msg


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

        Nop ->
            ( model, Cmd.none )

        TabMsg msg' ->
            let
                ( tabs', cmd', url ) =
                    Tabs.update msg' model.tabs
            in
                ( { model | tabs = tabs', current = url }, Cmd.none )

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
                ( { model | lang = lang', tabs = Tabs.model tabs', content = data.content }, Cmd.none )

        FetchFail _ ->
            ( model, Cmd.none )



-- VIEW


type alias Mdl =
    Material.Model


view : Model -> Html Msg
view =
    Html.Lazy.lazy view'


view' : Model -> Html Msg
view' model =
    Layout.render Mdl
        model.mdl
        [ Layout.fixedHeader
        ]
        { header = header model
        , drawer = []
        , tabs = ( [], [] )
        , main = [ viewBody model ]
        }


header : Model -> List (Html Msg)
header model =
    [ Layout.row
        [ css "height" "120px"
        , Color.background (Color.color Color.LightBlue Color.S50)
        ]
        [ Layout.title [] [ img [ src "theme/clq/css/img/logo3-simple.png" ] [] ]
        , Layout.spacer
        , Layout.navigation [] (List.map (App.map TabMsg) (Tabs.view model.tabs))
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


boxed : List (Options.Property a b)
boxed =
    [ css "margin" "auto"
    , css "padding-left" "8%"
    , css "padding-right" "8%"
    ]


viewBody : Model -> Html Msg
viewBody model =
    Options.div boxed
        [ grid [ noSpacing ]
            [ cell [ size All 12 ] [ text model.current ]
            ]
        ]


type alias Flags =
    { siteurl : String
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
