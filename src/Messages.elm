module Messages exposing (..)

import Http
import Types exposing (BackendData)
import Material


type Msg
    = Mdl (Material.Msg Msg)
    | Nop
    | RequestResult (Result Http.Error BackendData)
    | ChangePage String
    | ChangeLanguage String
