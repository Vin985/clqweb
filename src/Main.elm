{- This file re-implements the Elm Counter example (1 counter) with elm-mdl
   buttons. Use this as a starting point for using elm-mdl components in your own
   app.
-}


port module Main exposing (..)

import Html.App as App
import Html exposing (..)
import Html.Attributes exposing (href, class, style, src)
import Material
import Material.Button as Button
import Material.Options exposing (css)
import Material.Layout as Layout
import Material.Color as Color
import Material.Icon as Icon
import Material.Options as Options exposing (css, when)
import Json.Decode exposing (..)
import Debug


-- MODEL
-- You have to add a field to your model where you track the `Material.Model`.
-- This is referred to as the "model container"


type alias Model =
    { count : Int
    , mdl :
        Material.Model
        -- Boilerplate: model store for any and all Mdl components you use.
    , selectedTab : Int
    , tabs : List Tab
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
    }



-- ACTION, UPDATE
-- You need to tag `Msg` that are coming from `Mdl` so you can dispatch them
-- appropriately.


type Msg
    = Increase
    | Reset
    | Mdl (Material.Msg Msg)
    | SelectTab Int
    | Nop
    | NavTabs Json.Decode.Value



-- Boilerplate: Msg clause for internal Mdl messages.


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        Increase ->
            ( { model | count = model.count + 1 }
            , Cmd.none
            )

        Reset ->
            ( { model | count = 0 }
            , Cmd.none
            )

        -- When the `Mdl` messages come through, update appropriately.
        Mdl msg' ->
            Material.update msg' model

        SelectTab num ->
            { model | selectedTab = num } ! []

        Nop ->
            ( model, Cmd.none )

        NavTabs value ->
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
            ( List.map (\x -> text x.title) model.tabs
            , [ css "justify-content" "flex-end"
              , css "font-weight" "bold"
              ]
            )
        , main = [ viewBody model ]
        }


header : Model -> List (Html Msg)
header model =
    [ Layout.row
        [ css "height" "170px"
        , Color.background (Color.color Color.LightBlue Color.S50)
        ]
        [ Layout.title [] [ img [ src "theme/clq/css/img/logo.png" ] [] ]
        , Layout.spacer
        , Layout.navigation []
            [ Layout.link [ Layout.onClick Nop ]
                [ Icon.i "phone"
                , text "(450) 788-2680"
                ]
            , Layout.link [ Layout.href "mailto:info@campinglequebecois.qc.ca" ]
                [ Icon.i "email"
                , text "info@campinglequebecois.qc.ca"
                ]
            ]
        ]
    ]


viewBody : Model -> Html Msg
viewBody model =
    case model.selectedTab of
        0 ->
            viewCounter model

        1 ->
            text "something else"

        _ ->
            text "404"


viewCounter : Model -> Html Msg
viewCounter model =
    div [ style [ ( "padding", "2rem" ) ] ]
        [ text ("Current count: " ++ toString model.count)
          {- We construct the instances of the Button component that we need, one
             for the increase button, one for the reset button. First, the increase
             button. The first three arguments are:

               - A Msg constructor (`Mdl`), lifting Mdl messages to the Msg type.
               - An instance id (the `[0]`). Every component that uses the same model
                 collection (model.mdl in this file) must have a distinct instance id.
               - A reference to the elm-mdl model collection (`model.mdl`).

             Notice that we do not have to add fields for the increase and reset buttons
             separately to our model; and we did not have to add to our update messages
             to handle their internal events.

             Mdl components are configured with `Options`, similar to `Html.Attributes`.
             The `Button.onClick Increase` option instructs the button to send the `Increase`
             message when clicked. The `css ...` option adds CSS styling to the button.
             See `Material.Options` for details on options.
          -}
        , Button.render Mdl
            [ 0 ]
            model.mdl
            [ Button.onClick Increase
            , css "margin" "0 24px"
            ]
            [ text "Increase" ]
        , Button.render Mdl
            [ 1 ]
            model.mdl
            [ Button.onClick Reset ]
            [ text "Reset" ]
        ]


type alias Tab =
    { current : Bool
    , url : String
    , parent : String
    , title : String
    }


defaultTab : Tab
defaultTab =
    { current = False
    , url = ""
    , parent = ""
    , title = "prout"
    }


port getNav : String -> Cmd msg


port nav : (Json.Decode.Value -> msg) -> Sub msg


navSub : Model -> Sub Msg
navSub model =
    nav NavTabs



-- Load Google Mdl CSS. You'll likely want to do that not in code as we
-- do here, but rather in your master .html file. See the documentation
-- for the `Material` module for details.


type alias Flags =
    { tabs : Json.Decode.Value
    }


init : Flags -> ( Model, Cmd Msg )
init flags =
    ( { model
        | mdl = Layout.setTabsWidth 1000 model.mdl
        , tabs = extractTabs flags
      }
    , Layout.sub0 Mdl
    )


extractTabs : Flags -> List Tab
extractTabs flags =
    let
        one =
            Debug.log "flags" flags
    in
        case Json.Decode.decodeValue decodeTabs flags.tabs of
            Err msg ->
                let
                    two =
                        Debug.log "err" msg
                in
                    [ { defaultTab | title = "error" } ]

            Ok tabs ->
                tabs


decodeTabs : Decoder (List Tab)
decodeTabs =
    at [] (list decodeTab)


decodeTab : Decoder Tab
decodeTab =
    Json.Decode.object4 Tab
        ("current" := bool)
        ("url" := string)
        ("parent" := string)
        ("title" := string)


main : Program Flags
main =
    App.programWithFlags
        { init = init
        , view = view
        , subscriptions = .mdl >> Layout.subs Mdl
        , update = update
        }
