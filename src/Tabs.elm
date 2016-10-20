module Tabs exposing (view)

import Material.Icon as Icon
import Material.Options exposing (css)
import Html exposing (..)
import Html exposing (..)
import Material.Layout as Layout
import Material.Options as Options exposing (css, when, cs, nop)
import String exposing (..)
import Messages exposing (..)
import Types exposing (Tabs, Tab, defaultTab)


{-
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
-}


view : Tabs -> String -> List (Html Msg)
view tabs current =
    let
        i name =
            Icon.view name [ css "width" "40px" ]

        padding =
            css "padding-right" "24px"
    in
        List.map (viewTab current) tabs



{- ++ [ Menu.render Mdl
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
-}


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
