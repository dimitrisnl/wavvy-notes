import { routes as dashboard }  from './dashboard'
import { routes as notes }      from './notes'
import { routes as auth }        from './auth'

export default [...auth, ...notes, ...dashboard]
