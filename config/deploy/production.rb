role :web, 'apps.brightmarch.com'
role :db,  'apps.brightmarch.com', :primary => true

set :deploy_to, '/srv/http/apps/picto'
