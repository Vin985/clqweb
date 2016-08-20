port module Main exposing (..)

import Html exposing (..)
import Html.App as App
import Http
import Task


main =
    App.program
        { init = init
        , view = view
        , update = update
        , subscriptions = subscriptions
        }



-- MODEL


type alias Model =
    { data : String
    , slug : String
    }


init : ( Model, Cmd Msg )
init =
    ( Model "" "", Cmd.batch [ getData, getSlug "toto" ] )



-- UPDATE


type Msg
    = FetchSucceed String
    | FetchFail Http.Error
    | Slug String


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        FetchSucceed data' ->
            ( { model | data = data' }, Cmd.none )

        FetchFail _ ->
            ( model, Cmd.none )

        Slug slug' ->
            ( { model | slug = slug' }, Cmd.none )



-- VIEW


view : Model -> Html Msg
view model =
    div []
        [ h2 [] [ text model.data ]
        , br [] []
        , h2 [] [ text model.slug ]
        ]



-- SUBSCRIPTIONS


port getSlug : String -> Cmd msg


port slug : (String -> msg) -> Sub msg


subscriptions : Model -> Sub Msg
subscriptions model =
    slug Slug



-- HTTP


getData : Cmd Msg
getData =
    let
        url =
            "http://localhost/clqweb/theme/clq/php/test.php"
    in
        Task.perform FetchFail FetchSucceed (Http.getString url)
