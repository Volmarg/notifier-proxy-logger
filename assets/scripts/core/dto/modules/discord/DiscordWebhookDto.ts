/**
 * @description returns representation of backend dto which contains the defined discord webhook data
 */
export default class DiscordWebhookDto
{

    /**
     * @param id {number}
     */
    private _id: number|null = null;

    /**
     * @param username {string}
     */
    private _username:string = "";

    /**
     * @param webhookUrl {string}
     */
    private _webhookUrl:string = "";

    /**
     * @param description {string}
     */
    private _description:string = "";

    /**
     * @param webhookName {string}
     */
    private _webhookName:string = "";

    get username(): string {
        return this._username;
    }

    set username(value: string) {
        this._username = value;
    }

    get webhookUrl(): string {
        return this._webhookUrl;
    }

    set webhookUrl(value: string) {
        this._webhookUrl = value;
    }

    get description(): string {
        return this._description;
    }

    set description(value: string) {
        this._description = value;
    }

    get webhookName(): string {
        return this._webhookName;
    }

    set webhookName(value: string) {
        this._webhookName = value;
    }

    get id(): number {
        return this._id;
    }

    set id(value: number) {
        this._id = value;
    }

    /**
     * Will produce dto from json
     *
     * @param json
     */
    public static fromJson(json: string): DiscordWebhookDto
    {
        let object = JSON.parse(json);
        let dto    = new DiscordWebhookDto();

        dto.id          = object.id;
        dto.username    = object.username;
        dto.webhookUrl  = object.webhookUrl;
        dto.description = object.description;
        dto.webhookName = object.webhookName;

        return dto;
    }

}