<?php
/**
 * Created by PhpStorm.
 * User: victo
 * Date: 12/04/2018
 * Time: 19:41
 */

namespace pwbox\Model\Implementation;


use Doctrine\DBAL\Connection;
use pwbox\Model\User;
use pwbox\Model\File;
use pwbox\Model\UserRepository;

class DoctrineUserRepository implements UserRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user)
    {
        //comprovar que el usuario y email no existen

        $sql = "SELECT * FROM user WHERE username =:username OR email =:email";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->execute();
        $count = $stmt->fetchColumn(0);

        if($count>0){
            return false;
        }
        else {

            $sql = "INSERT INTO user(username, email, birthdate, password, created_at, updated_at) VALUES (:username, :email, :birthdate, :password, :createdAt, :updatedAt)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue("username", $user->getUsername(), 'string');
            $stmt->bindValue("email", $user->getEmail(), 'string');
            $stmt->bindValue("birthdate", $user->getBirthdate(), 'string');
            $stmt->bindValue("password", md5($user->getPassword()), 'string');
            $stmt->bindValue("createdAt", $user->getCreatedAt()->format(self::DATE_FORMAT));
            $stmt->bindValue("updatedAt", $user->getUpdatedAt()->format(self::DATE_FORMAT));
            $stmt->execute();

            $sql = "SELECT LAST_INSERT_ID()";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $user = $stmt->fetchAll()[0];
            $id_user = $user['LAST_INSERT_ID()'];

            $sql = "INSERT INTO folder(name, isroot, fk_parent) VALUES ('rootfolder', true, 0)";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            $sql = "SELECT LAST_INSERT_ID()";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $folder = $stmt->fetchAll()[0];
            $id_folder = $folder['LAST_INSERT_ID()'];

            $sql = "INSERT INTO root_users VALUES (:fk_user, :fk_folder)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue("fk_user", $id_user, 'integer');
            $stmt->bindValue("fk_folder", $id_folder, 'integer');
            $stmt->execute();
        }
        return true;

    }

    public function login($username, $password)
    {
        $sql = "SELECT id FROM user WHERE username= :username AND password= :password";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("username", $username, 'string');
        $stmt->bindValue("password", md5($password), 'string');
        $stmt->execute();
        $count = $stmt->fetchColumn(0);
        $stmt->execute();
        $user = $stmt->fetch();
        $_SESSION['id'] = $user['id'];
        if($count>0){
           return true;
        }
        return false;
    }

    public function load($id){
        $sql = "SELECT * FROM folder LEFT JOIN root_users ON root_users.fk_folder=folder.id where root_users.fk_user =:id and folder.isroot=true";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $id, 'integer');
        $stmt->execute();
        $folders = $stmt->fetchAll();
        return $folders[0];
    }

    public function loadFolders($id){
        $sql = "SELECT * FROM folder WHERE fk_parent=:id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $id, 'integer');
        $stmt->execute();
        $folders = $stmt->fetchAll();
        return $folders;
    }

    public function loadFiles($id){
        $sql = "SELECT * FROM file WHERE fk_folder=:id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $id, 'integer');
        $stmt->execute();
        $folders = $stmt->fetchAll();
        return $folders;
    }

    public function findUser($id){
        $sql = "SELECT * FROM user WHERE id=:id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $id, 'integer');
        $stmt->execute();
        $user = $stmt->fetchAll();
        return $user[0];
    }

    public function updatePass($data){
        $password = $data['password'];
        $id = $data['id'];
        $sql = "UPDATE user SET password = :password WHERE id= :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("password", md5($password), 'string');
        $stmt->bindValue("id", $id, 'integer');
        $stmt->execute();

        $sql = "SELECT * FROM user WHERE id=:id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $id, 'integer');
        $stmt->execute();
        $user = $stmt->fetchAll();
        return $user[0];
    }

    public function updateData(User $user){

        $sql = "UPDATE user SET email= :email, birthdate= :birthdate, updated_at = :updated_at WHERE id= :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->bindValue("birthdate", $user->getBirthdate(), 'string');
        $stmt->bindValue("id", $user->getId(), 'integer');
        $stmt->bindValue("updated_at", $user->getUpdatedAt()->format(self::DATE_FORMAT));
        $stmt->execute();

        $sql = "SELECT * FROM user WHERE id=:id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $user->getId(), 'integer');
        $stmt->execute();
        $user = $stmt->fetchAll();
        return $user[0];
    }

    public function addFolder($data){
        $name = $data["name"];
        $folder = $data["folder"];

        $sql = "INSERT INTO folder(name, isroot, fk_parent) values(:name, false, :fk_parent)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("name", $name, 'string');
        $stmt->bindValue("fk_parent", $folder, 'integer');
        $stmt->execute();
    }

    public function renameFolder($data){
        $name = $data["name"];
        $folder = $data["folder"];

        $sql = "UPDATE folder SET name= :name WHERE id= :folder";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("name", $name, 'string');
        $stmt->bindValue("folder", $folder, 'integer');
        $stmt->execute();
    }

    public function renameFile($data){
        $name = $data["name"];
        $file = $data["file"];

        $sql = "UPDATE file SET name= :name WHERE id= :file";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("name", $name, 'string');
        $stmt->bindValue("file", $file, 'string');
        $stmt->execute();
    }

    public function getParent($data){
        $id_folder = $data["folder"];
        $sql = "SELECT fk_parent from folder where id=:id_folder";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_folder", $id_folder, 'integer');
        $stmt->execute();
        $folder = $stmt->fetch();
        return $folder["fk_parent"];
    }

    public function getFolder($data){
        $id_folder = $data["file"];
        $sql = "SELECT fk_folder from file where id=:id_folder";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_folder", $id_folder, 'integer');
        $stmt->execute();
        $folder = $stmt->fetch();
        return $folder["fk_folder"];
    }

    public function removeFolder($data){
        $folder = $data["folder"];

        $sql = "DELETE FROM folder WHERE id =:folder";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("folder", $folder, 'integer');
        $stmt->execute();
    }

    public function removeFile($data){
        $file = $data["file"];

        $sql = "DELETE FROM file WHERE id =:file";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("file", $file, 'string');
        $stmt->execute();
    }

    public function removeUser($data){
        $id = $data["user_id"];
        $sql = "DELETE FROM user WHERE id =:id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $id, 'integer');
        $stmt->execute();
    }

    public function getSharedFolders($data){
        $id_user = $data['id'];
        $sql = "select * from folder left join shared_folders on folder.id = shared_folders.fk_folder where shared_folders.fk_user =:id_user;";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_user", $id_user, 'integer');
        $stmt->execute();
        $folders = $stmt->fetchAll();
        return $folders;
    }

    public function getSharedFiles($data){
        $id_user = $data['id'];
        $sql = "select * from file left join shared_folders on file.fk_folder = shared_folders.fk_folder where shared_folders.fk_user =:id_user";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_user", $id_user, 'integer');
        $stmt->execute();
        $files = $stmt->fetchAll();
        return $files;
    }

    public function shareFolder($data){
        $folder = $data['folder_id'];
        $email = $data['user_email'];
        $sql = "select id from user where email = :email";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("email", $email, 'string');
        $stmt->execute();
        $count = $stmt->fetchColumn(0);
        $stmt->execute();
        $user = $stmt->fetch();
        $id = $user['id'];
        if($count>0){
            $sql = "select * from shared_folders where fk_folder = :folder and fk_user = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue("folder", $folder, 'integer');
            $stmt->bindValue("id", $id, 'integer');
            $stmt->execute();
            $count = $stmt->fetchColumn(0);
            if($count==0){
                $sql = "insert into shared_folders values(:folder, :id)";
                $stmt = $this->connection->prepare($sql);
                $stmt->bindValue("folder", $folder, 'integer');
                $stmt->bindValue("id", $id, 'integer');
                $stmt->execute();
            }
            return true;
        }
        return false;
    }

    public function addFile(File $file){
        $sql = "insert into file(id, name, fk_folder, size, extension) values(:id, :name, :fk_folder, :size, :extension)";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue("id", $file->getId(), 'string');
        $stmt->bindValue("name", $file->getFilename(), 'string');
        $stmt->bindValue("fk_folder", $file->getFolder(), 'integer');
        $stmt->bindValue("size", $file->getSize(), 'float');
        $stmt->bindValue("extension", $file->getExtension(), 'string');
        $stmt->execute();
    }
}