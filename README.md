# telescope-kenny

## Create a network
```$ podman network create telescope```

## Check it exists
```
$ podman network ls
NETWORK ID    NAME        DRIVER
2f259bab93aa  podman      bridge
65b801d1c77d  telescope   bridge
```

## Login:
```$ podman login registry.redhat.io```

## Create psql volume
```$ podman volume create postgresql```

## Create & run postgres db
```$ podman run -itd --network telescope --env=POSTGRESQL_PASSWORD=passw0rd --env=POSTGRESQL_USER=telescope --env=POSTGRESQL_DATABASE=telescope -v postgresql:/var/lib/postgresql/data:Z --name postgresql registry.redhat.io/rhel9/postgresql-16:1-14```

## Check:
```
$ podman container list
CONTAINER ID  IMAGE                                        COMMAND         CREATED         STATUS         PORTS       NAMES
9c4e1c315468  registry.redhat.io/rhel9/postgresql-16:1-14  run-postgresql  38 seconds ago  Up 38 seconds              postgresql
```    

## Import the data
```$ podman exec -i -u postgres postgresql psql < telescope-kenny.sql```

### Check:
```    
$ podman exec -ti -u postgres postgresql psql
    psql (16.1)
    Type "help" for help.
    
postgres=# \d+
                                                      List of relations
     Schema |            Name            |   Type   |   Owner   | Persistence | Access method |    Size    | Description 
    --------+----------------------------+----------+-----------+-------------+---------------+------------+-------------
     public | capability                 | table    | telescope | permanent   | heap          | 8192 bytes | 
     public | capability_history         | table    | telescope | permanent   | heap          | 0 bytes    | 
     public | capability_history_id_seq  | sequence | telescope | permanent   |               | 8192 bytes | 
     public | capability_id_seq          | sequence | telescope | permanent   |               | 8192 bytes | 
     public | domain                     | table    | telescope | permanent   | heap          | 8192 bytes | 
     public | domain_id_seq              | sequence | telescope | permanent   |               | 8192 bytes | 
     public | flag                       | table    | telescope | permanent   | heap          | 8192 bytes | 
     public | flag_id_seq                | sequence | telescope | permanent   |               | 8192 bytes | 
     public | integration_id_seq         | sequence | telescope | permanent   |               | 8192 bytes | 
     public | integration_methods        | table    | telescope | permanent   | heap          | 16 kB      | 
     public | integration_methods_id_seq | sequence | telescope | permanent   |               | 8192 bytes | 
     public | integrations               | table    | telescope | permanent   | heap          | 16 kB      | 
     public | profiles                   | table    | postgres  | permanent   | heap          | 16 kB      | 
     public | profiles_id_seq            | sequence | postgres  | permanent   |               | 8192 bytes | 
    (14 rows)

    postgres=# select * from profiles;
 id | name |   description   |       domains       
----+------+-----------------+---------------------
  2 | ZTA  | ZTA domains     | {13,14,15,16,17,18}
  1 | Core | Default domains | {1,2,3,4,5}
(2 rows)
```

## Build the app
```
$ podman build -t telescope:latest . 
```
(Note: don't forget the . at the end of the above command!)
## Output
```
STEP 1/5: FROM registry.access.redhat.com/ubi9/php-81:latest
STEP 2/5: MAINTAINER Chris Jenkins "chrisj@redhat.com"
--> Using cache aada37655cec7a66ff3745bea051aebb3c6dbdf40b1b810335dc3b2f4eadfd0e
--> aada37655cec
STEP 3/5: EXPOSE 8080
--> Using cache c6d215e24d2958f1b50f41693432787afa42468a70d12448ab80ab25f25a7bd5
--> c6d215e24d29
STEP 4/5: COPY . /opt/app-root/src
--> c916517caea7
STEP 5/5: CMD /bin/bash -c 'php -S 0.0.0.0:8080'
COMMIT telescope:latest
--> 29982d8f97ec
Successfully tagged localhost/telescope:latest
29982d8f97ece7e5c674bfc19ed380dc7689b3611960c7561608adec6694e64a
```

## Check it built ok
```
$ podman image list | grep telescope
localhost/telescope                           latest      9065a9320b14  About a minute ago  937 MB
```

## Run the app
```
$ podman run -p 8080:8080 --network telescope --env=POSTGRESQL_PASSWORD=passw0rd --env=POSTGRESQL_USER=telescope --env=POSTGRESQL_DATABASE=postgres --env=POSTGRESQL_HOST=postgresql localhost/telescope

[Thu May 30 06:34:02 2024] PHP 8.1.27 Development Server (http://0.0.0.0:8080) started
```

You can then open a browser and navigate to [http://localhost:8080](http://localhost:8080) to open the application.



