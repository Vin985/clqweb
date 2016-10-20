module Messages exposing (..)

import Http
import Types exposing (BackendData)
import Material


type Msg
    = Mdl (Material.Msg Msg)
    | Nop
    | FetchFail Http.Error
    | FetchSucceed BackendData
    | ChangePage String
    | ChangeLanguage String
