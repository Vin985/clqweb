{- This file re-implements the Elm Counter example (1 counter) with elm-mdl
   buttons. Use this as a starting point for using elm-mdl components in your own
   app.
-}


port module Main exposing (..)

import Html exposing (..)
import Html.Lazy
import Html.Attributes exposing (href, class, style, src, attribute)
import Material
import Material.Options exposing (css)
import Material.Grid exposing (..)
import Material.Layout as Layout
import Material.Color as Color
import Material.Options as Options exposing (css, when, cs)
import Http
import Task
import String
import Navigation
import Tabs exposing (view)
import HtmlParser
import RouteUrl as Routing
import Messages exposing (..)
import Types exposing (Tabs, BackendData, Languages, Page, defaultPage)
import JsonDecoder
import Dict exposing (Dict)
import Constants


-- MODEL
-- You have to add a field to your model where you track the `Material.Model`.
-- This is referred to as the "model container"


type alias Model =
    { mdl : Material.Model
    , tabs : Tabs
    , siteUrl : String
    , languages : Languages
    , current : String
    , page : Page
    }


type alias UrlArgs =
    List ( String, String )



-- `Material.model` provides the initial model


model : Model
model =
    { mdl =
        Material.model
        -- Boilerplate: Always use this initial Mdl model store.
    , tabs = []
    , siteUrl = ""
    , languages = Languages "fr" []
    , current = "index"
    , page = defaultPage
    }



-- ACTION, UPDATE
-- You need to tag `Msg` that are coming from `Mdl` so you can dispatch them
-- appropriately.


getData : String -> UrlArgs -> Cmd Msg
getData siteUrl args =
    let
        args =
            ( "id", "fetchdata" ) :: args

        url =
            Http.url (siteUrl ++ "index.php") args
    in
        Task.perform FetchFail FetchSucceed (Http.get JsonDecoder.decodeData url)


getPageData : String -> Model -> Cmd Msg
getPageData page model =
    let
        args =
            [ ( "page", page ) ]
    in
        getData model.siteUrl args


changeLanguage : String -> Model -> Cmd Msg
changeLanguage lang model =
    let
        args =
            [ ( "page", model.current ), ( "setlang", lang ) ]

        one =
            Debug.log "changelang" lang
    in
        getData model.siteUrl args


getInit : String -> Cmd Msg
getInit siteUrl =
    getData siteUrl [ ( "page", "init" ) ]



{- getInit : String -> String -> Decoder -> Cmd Msg -}
-- Boilerplate: Msg clause for internal Mdl messages.


changePage : String -> Model -> Cmd Msg
changePage url model =
    if url /= model.current && url /= "" then
        getPageData url model
    else
        Cmd.none


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        -- When the `Mdl` messages come through, update appropriately.
        Mdl msg' ->
            Material.update msg' model

        Nop ->
            ( model, Cmd.none )

        ChangePage url ->
            ( { model | current = url }, changePage url model )

        ChangeLanguage lang ->
            ( model, changeLanguage lang model )

        {- TabMsg msg' ->
           let
               ( tabs', cmd', url ) =
                   Tabs.update msg' model.tabs
           in
               ( { model | tabs = tabs', current = url }
               , Cmd.batch [ Cmd.map TabMsg cmd', changePage url model ]
               )
        -}
        FetchSucceed data ->
            let
                langs =
                    case data.lang of
                        Nothing ->
                            model.languages

                        Just lang ->
                            lang

                tabs_ =
                    case data.tabs of
                        Nothing ->
                            {- TODO : add isEmpty to Tabs model -}
                            if not <| List.isEmpty model.tabs then
                                model.tabs
                            else
                                []

                        Just tabs ->
                            tabs

                one =
                    Debug.log "data" data
            in
                ( { model | languages = langs, tabs = tabs_, page = data.page }, Cmd.none )

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
        , main = [ viewBody model.page ]
        }


header : Model -> List (Html Msg)
header model =
    [ Layout.row
        [ css "height" "120px"
        , Color.background (Color.color Color.LightBlue Color.S50)
        ]
        [ Layout.title [] [ img [ src "theme/clq/css/img/logo3-simple.png" ] [] ]
        , Layout.spacer
        , Layout.navigation []
            (Tabs.view model.tabs model.current
                ++ [ viewLanguages model.languages ]
            )
        ]
    ]


boxed : List (Options.Property a b)
boxed =
    [ css "margin" "auto"
    , css "padding-left" "8%"
    , css "padding-right" "8%"
    ]


viewLanguages : Languages -> Html Msg
viewLanguages langs =
    case langs.other of
        lang :: [] ->
            Layout.link [ Layout.onClick (ChangeLanguage lang) ] [ text <| getLanguageText lang Constants.langsText ]

        _ ->
            text "languages"


getLanguageText : String -> Dict String String -> String
getLanguageText key texts =
    Maybe.withDefault "Key not found" <| Dict.get key texts


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



-- Routing


urlOf : Model -> String
urlOf model =
    "#" ++ model.current


delta2url : Model -> Model -> Maybe Routing.UrlChange
delta2url model1 model2 =
    if model1.current /= model2.current || model1.languages.current /= model2.languages.current then
        { entry = Routing.NewEntry
        , url = urlOf model2
        }
            |> Just
    else
        Nothing


location2messages : Navigation.Location -> List Msg
location2messages location =
    [ case String.dropLeft 1 location.hash of
        "" ->
            ChangePage "index"

        x ->
            ChangePage x
    ]


type alias Flags =
    { siteUrl : String
    }


init : Flags -> ( Model, Cmd Msg )
init flags =
    ( { model
        | mdl = Layout.setTabsWidth 800 model.mdl {- , tabs = extractTabs flags.tabs -}
        , siteUrl = flags.siteUrl
      }
    , Cmd.batch [ Material.init Mdl, getInit flags.siteUrl ]
    )


main : Program Flags
main =
    Routing.programWithFlags
        { delta2url = delta2url
        , location2messages = location2messages
        , init = init
        , view = view
        , subscriptions = Material.subscriptions Mdl
        , update = update
        }
