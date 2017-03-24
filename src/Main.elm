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
import String
import Navigation
import Tabs exposing (view)
import HtmlParser
import RouteUrl as Routing exposing (RouteUrlProgram, UrlChange)
import RouteUrl.Builder as Builder exposing (Builder, builder, path, replacePath, prependToPath)
import Messages exposing (..)
import Types exposing (Tabs, BackendData, Languages, Page, defaultPage)
import JsonDecoder
import Json.Encode
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


createUrl : String -> List ( String, String ) -> String
createUrl baseUrl query =
    case query of
        [] ->
            baseUrl

        _ ->
            let
                queryPairs =
                    query |> List.map (\( key, value ) -> Http.encodeUri key ++ "=" ++ Http.encodeUri value)

                queryString =
                    queryPairs |> String.join "&"
            in
                baseUrl ++ "?" ++ queryString


getData : String -> UrlArgs -> Cmd Msg
getData siteUrl args =
    let
        args_ =
            ( "id", "fetchdata" ) :: args

        url =
            createUrl (siteUrl ++ "index.php") args_
    in
        Http.send RequestResult <|
            Http.get url JsonDecoder.decodeData


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
        Mdl msg_ ->
            Material.update Mdl msg_ model

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
        RequestResult (Ok data) ->
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
                            model.tabs

                        Just tabs ->
                            tabs

                one =
                    Debug.log "data" data
            in
                ( { model | languages = langs, tabs = tabs_, page = data.page }, Cmd.none )

        RequestResult (Err err) ->
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
    Html.Lazy.lazy view_


view_ : Model -> Html Msg
view_ model =
    Layout.render Mdl
        model.mdl
        [ Layout.fixedHeader
        , Layout.scrolling
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
        , Layout.navigation []
            (Tabs.view model.tabs model.current
                ++ [ viewLanguages model.languages ]
            )
        ]
    ]


viewLanguages : Languages -> Html Msg
viewLanguages langs =
    case langs.other of
        lang :: [] ->
            Layout.link [ Options.onClick (ChangeLanguage lang) ]
                [ text <|
                    getLanguageText lang Constants.langsText
                ]

        _ ->
            text "languages"


getLanguageText : String -> Dict String String -> String
getLanguageText key texts =
    Maybe.withDefault "Key not found" <| Dict.get key texts


boxed : List (Options.Property a b)
boxed =
    [ css "margin" "auto"
    , css "padding-left" "8%"
    , css "padding-right" "8%"
    ]


viewBody : Model -> Html Msg
viewBody model =
    let
        page =
            model.page

        a =
            Debug.log "page" page

        subnav =
            viewSubTabs model

        htmlContent =
            HtmlParser.parse page.content |> htmlNodesToElm
    in
        Options.div boxed
            [ grid [ noSpacing ]
                [ cell [ size All 12 ]
                    [ grid [ noSpacing ]
                        [ cell [ size All 12 ] [ Options.div [ cs "elm-subnav" ] subnav ] ]
                    , grid []
                        [ cell [ size All 12 ]
                            ([ Options.styled h3
                                [ css "margin-top" "0"
                                , Options.attribute <|
                                    Html.Attributes.property "innerHTML" (Json.Encode.string page.title)
                                ]
                                []
                             ]
                                ++ htmlContent
                            )
                        ]
                    ]
                ]
            ]


viewSubTabs : Model -> List (Html Msg)
viewSubTabs model =
    let
        children =
            model.page.children

        a =
            Debug.log "children" children
    in
        case children of
            Nothing ->
                []

            Just children ->
                Tabs.viewSubTabs children model.current


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

                children_ =
                    htmlNodesToElm children
            in
                Html.node element attrs children_

        HtmlParser.Comment comment ->
            Html.text ""


htmlAttributesToElm : HtmlParser.Attributes -> List (Html.Attribute Msg)
htmlAttributesToElm attrs =
    List.map (\( x, y ) -> Html.Attributes.attribute x y) attrs



-- Routing


urlOf : Model -> String
urlOf model =
    "#" ++ model.current


delta2builder : Model -> Model -> Maybe Builder
delta2builder previousModel currentModel =
    let
        sitePath =
            currentModel.siteUrl
                |> Builder.fromUrl
                |> Builder.path

        a =
            Debug.log "path" sitePath
    in
        if previousModel.current /= currentModel.current || previousModel.languages.current /= currentModel.languages.current then
            builder
                |> Builder.replacePath sitePath
                |> Builder.appendToPath [ currentModel.current ]
                |> Just
        else
            Nothing


delta2url : Model -> Model -> Maybe UrlChange
delta2url previous current =
    Maybe.map Builder.toUrlChange <|
        delta2builder previous current



{--
delta2url : Model -> Model -> Maybe Routing.UrlChange
delta2url model1 model2 =
    if model1.current /= model2.current || model1.languages.current /= model2.languages.current then
        { entry = Routing.NewEntry
        , url = urlOf model2
        }
            |> Just
    else
        Nothing
--}


url2messages : Navigation.Location -> List Msg
url2messages location =
    builder2messages (Builder.fromUrl location.href)


builder2messages : Builder -> List Msg
builder2messages builder =
    case List.reverse <| Builder.path builder of
        first :: rest ->
            [ ChangePage first ]

        _ ->
            [ ChangePage "index" ]



{--
location2messages : Navigation.Location -> List Msg
location2messages location =
  let
    one =
        Debug.log "err" location
  in
    [ case String.dropLeft 1 location.hash of
        "" ->
            ChangePage "index"

        x ->
            ChangePage x
    ]
--}


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


main : RouteUrlProgram Flags Model Msg
main =
    Routing.programWithFlags
        { delta2url = delta2url
        , location2messages = url2messages
        , init = init
        , view = view
        , subscriptions = Material.subscriptions Mdl
        , update = update
        }
