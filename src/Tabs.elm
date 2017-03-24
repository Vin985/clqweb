module Tabs exposing (view, viewSubTabs)

import Html exposing (..)
import Html.Attributes
import Material.Options exposing (css)
import Material.Layout as Layout
import Material.Options as Options exposing (css, when, cs, nop)
import String exposing (..)
import Messages exposing (..)
import Json.Encode
import Types exposing (Tabs, Tab, defaultTab)


view : Tabs -> String -> List (Html Msg)
view tabs current =
    List.map (viewTab True current) tabs


viewSubTabs : Tabs -> String -> List (Html Msg)
viewSubTabs tabs current =
    List.map (viewTab False current) tabs


viewTab : Bool -> String -> Tab -> Html Msg
viewTab top current tab =
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
            ([ Options.onClick (ChangePage tab.url)
             , when (tab.url == current) (cs "is-active")
             , when (not top) (cs "subtab")
             , Options.attribute <|
                Html.Attributes.property "innerHTML" (Json.Encode.string (toUpper tab.title))
             ]
                ++ fontStyle
            )
            []
