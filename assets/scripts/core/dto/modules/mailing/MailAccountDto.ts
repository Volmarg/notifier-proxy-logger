/**
 * @description returns dto representation of backend dto which consist of Mail Account Entity data.
 */
export default class MailAccountDto
{
    private _id: number;

    private _client: string;

    private _name: string;

    private _login: string;

    private _password: string;

    get id(): number {
        return this._id;
    }

    set id(value: number) {
        this._id = value;
    }

    get client(): string {
        return this._client;
    }

    set client(value: string) {
        this._client = value;
    }

    get name(): string {
        return this._name;
    }

    set name(value: string) {
        this._name = value;
    }

    get login(): string {
        return this._login;
    }

    set login(value: string) {
        this._login = value;
    }

    get password(): string {
        return this._password;
    }

    set password(value: string) {
        this._password = value;
    }

    /**
     * Will produce dto from json
     *
     * @param json
     */
    public static fromJson(json: string): MailAccountDto
    {
        let object = JSON.parse(json);
        let dto    = new MailAccountDto();

        dto.id       = object.id;
        dto.client   = object.client;
        dto.name     = object.name;
        dto.login    = object.login;
        dto.password = object.password;

        return dto;
    }

}