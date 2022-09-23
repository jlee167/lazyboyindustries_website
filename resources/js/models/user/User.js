class User{
    id;
    username;
    email;
    streamKey;
    status;
    twoFactorAuth;
    authProvider;
    imageUrl;


    constructor(DTO){
        this.id = DTO.id;
        this.username = DTO.username;
        this.email = DTO.email;
        this.streamKey = DTO.stream_key;
        this.status = DTO.status;
        this.twoFactorAuth = DTO.is2FAenabled;
        this.authProvider = DTO.auth_provider;
        this.imageUrl = DTO.image_url;
    }
}

export default User;
