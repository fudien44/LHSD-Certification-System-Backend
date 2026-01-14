import type { VerticalNavItems } from '@layouts/types'
import application from './application'
import dashboard from './dashboard'
import manage from './manage'

export default [...dashboard, ...application, ...manage] as VerticalNavItems
// export default [...dashboard, ...application, ...lnd, ...pm, ...rnr, ...manage] as VerticalNavItems
