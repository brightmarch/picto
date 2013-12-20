role :web, 'picto.brightmarch.com'
role :db,  'picto.brightmarch.com', :primary => true

set :deploy_to, '/srv/http/apps/picto'
