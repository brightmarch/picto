role :web, 'staging.brightmarch.com'
role :db,  'staging.brightmarch.com', :primary => true

set :deploy_to, '/srv/http/apps/picto'
