{- This file re-implements the Elm Counter example (1 counter) with elm-mdl
   buttons. Use this as a starting point for using elm-mdl components in your own
   app.
-}


port module Main exposing (..)

import Html.App as App
import Html exposing (..)
import Html.Lazy
import Html.Attributes exposing (href, class, style, src, attribute)
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
import HtmlParser


-- MODEL
-- You have to add a field to your model where you track the `Material.Model`.
-- This is referred to as the "model container"


type alias Page =
    { title : String
    , url : String
    , content : String
    }


defaultPage : Page
defaultPage =
    { title = ""
    , url = "index"
    , content = ""
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
    , current : Page
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
    , current = defaultPage
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

                cmd =
                    if url /= "" then
                        getData model.siteurl url decodeData
                    else
                        Cmd.none
            in
                ( { model | tabs = tabs' }, Cmd.batch [ Cmd.map TabMsg cmd', cmd ] )

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
                            {- TODO : add isEmpty to Tabs model -}
                            if not <| List.isEmpty model.tabs.tabs then
                                model.tabs
                            else
                                Tabs.model []

                        Just tabs ->
                            Tabs.model tabs

                page =
                    { url = data.url, content = data.content, title = data.title }

                one =
                    Debug.log "lang" data.lang

                two =
                    Debug.log "siteurl" data.tabs
            in
                ( { model | lang = lang', tabs = tabs', current = page }, Cmd.none )

        FetchFail err ->
            let
                one =
                    Debug.log "err" err
            in
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
        , main = [ viewBody model.current ]
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
        ]
    ]


boxed : List (Options.Property a b)
boxed =
    [ css "margin" "auto"
    , css "padding-left" "8%"
    , css "padding-right" "8%"
    ]


viewBody : Page -> Html Msg
viewBody page =
    let
        htmlContent =
            HtmlParser.parse page.content |> htmlNodesToElm
    in
        Options.div boxed
            [ grid [ noSpacing ]
                [ cell [ size All 12 ]
                    ([ h3 [] [ text page.title ] ] ++ htmlContent)
                ]
            ]


htmlNodesToElm : List HtmlParser.Node -> List (Html Msg)
htmlNodesToElm htmlList =
    List.map htmlNodeToElm htmlList


htmlNodeToElm : HtmlParser.Node -> Html Msg
htmlNodeToElm node =
    case node of
        HtmlParser.Text htmlText ->
            Html.text htmlText

        HtmlParser.Element element attributes children ->
            let
                attrs =
                    htmlAttributesToElm attributes

                children' =
                    htmlNodesToElm children
            in
                Html.node element attrs children'

        HtmlParser.Comment comment ->
            Html.text ""


htmlAttributesToElm : HtmlParser.Attributes -> List (Html.Attribute Msg)
htmlAttributesToElm attrs =
    List.map (\( x, y ) -> Html.Attributes.attribute x y) attrs


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
