/* eslint-disable */
import { TypedDocumentNode as DocumentNode } from '@graphql-typed-document-node/core';
export type Maybe<T> = T | null;
export type InputMaybe<T> = Maybe<T>;
export type Exact<T extends { [key: string]: unknown }> = { [K in keyof T]: T[K] };
export type MakeOptional<T, K extends keyof T> = Omit<T, K> & { [SubKey in K]?: Maybe<T[SubKey]> };
export type MakeMaybe<T, K extends keyof T> = Omit<T, K> & { [SubKey in K]: Maybe<T[SubKey]> };
export type MakeEmpty<T extends { [key: string]: unknown }, K extends keyof T> = { [_ in K]?: never };
export type Incremental<T> = T | { [P in keyof T]?: P extends ' $fragmentName' | '__typename' ? T[P] : never };
/** All built-in and custom scalars, mapped to their actual values */
export type Scalars = {
  ID: { input: string; output: string; }
  String: { input: string; output: string; }
  Boolean: { input: boolean; output: boolean; }
  Int: { input: number; output: number; }
  Float: { input: number; output: number; }
  /** A datetime string with format `Y-m-d H:i:s`, e.g. `2018-05-23 13:43:32`. */
  DateTime: { input: any; output: any; }
  Dimension: { input: any; output: any; }
  /** A [RFC 5321](https://tools.ietf.org/html/rfc5321) compliant email. */
  Email: { input: any; output: any; }
  /**
   *
   *         The `IntID` scalar type represents a unique integer identifier, often used to
   *         refetch an object or as a key for a cache. The IntID type appears in a JSON
   *         response as an integer; however, it is not intended to be human-readable.
   *         When expected as an input type, any integer input value will be accepted as an IntID.
   *
   */
  IntID: { input: any; output: any; }
  /** Arbitrary data encoded in JavaScript Object Notation. See https://www.json.org. */
  JSON: { input: any; output: any; }
  Phone: { input: any; output: any; }
  Price: { input: any; output: any; }
  Rating: { input: any; output: any; }
};

export type Address = {
  __typename?: 'Address';
  billingDefault: Scalars['Boolean']['output'];
  city: Scalars['String']['output'];
  companyName?: Maybe<Scalars['String']['output']>;
  contactEmail?: Maybe<Scalars['String']['output']>;
  contactPhone?: Maybe<Scalars['String']['output']>;
  countryId: Scalars['IntID']['output'];
  customerId: Scalars['IntID']['output'];
  deliveryInstructions?: Maybe<Scalars['String']['output']>;
  firstName: Scalars['String']['output'];
  id: Scalars['IntID']['output'];
  lastName: Scalars['String']['output'];
  lineOne: Scalars['String']['output'];
  lineThree?: Maybe<Scalars['String']['output']>;
  lineTwo?: Maybe<Scalars['String']['output']>;
  postcode: Scalars['String']['output'];
  shippingDefault: Scalars['Boolean']['output'];
  state?: Maybe<Scalars['String']['output']>;
  title?: Maybe<Scalars['String']['output']>;
};

export type AddressInput = {
  billingDefault: Scalars['Boolean']['input'];
  city: Scalars['String']['input'];
  companyName?: InputMaybe<Scalars['String']['input']>;
  contactEmail?: InputMaybe<Scalars['Email']['input']>;
  contactPhone?: InputMaybe<Scalars['Phone']['input']>;
  countryId: Scalars['IntID']['input'];
  deliveryInstructions?: InputMaybe<Scalars['String']['input']>;
  firstName: Scalars['String']['input'];
  id?: InputMaybe<Scalars['IntID']['input']>;
  lastName: Scalars['String']['input'];
  lineOne: Scalars['String']['input'];
  lineThree?: InputMaybe<Scalars['String']['input']>;
  lineTwo?: InputMaybe<Scalars['String']['input']>;
  postcode: Scalars['String']['input'];
  shippingDefault: Scalars['Boolean']['input'];
  state?: InputMaybe<Scalars['String']['input']>;
  title?: InputMaybe<Scalars['String']['input']>;
};

export type Brand = WithUrls & {
  __typename?: 'Brand';
  defaultUrl?: Maybe<Url>;
  id: Scalars['IntID']['output'];
  name: Scalars['String']['output'];
  urls?: Maybe<Array<Maybe<Url>>>;
};

export type CapturePaymentIntentInput = {
  paymentIntentId: Scalars['String']['input'];
};

export type Cart = {
  __typename?: 'Cart';
  id: Scalars['IntID']['output'];
  lines: Array<Maybe<CartLine>>;
  user?: Maybe<User>;
};

export type CartLine = {
  __typename?: 'CartLine';
  id: Scalars['IntID']['output'];
  purchasable: ProductVariant;
  quantity: Scalars['Int']['output'];
};

export type ChatMessage = {
  __typename?: 'ChatMessage';
  body: Scalars['String']['output'];
  chatRoom: ChatRoom;
  createdAt: Scalars['DateTime']['output'];
  customer: Customer;
  id: Scalars['IntID']['output'];
};

/** A paginated list of ChatMessage items. */
export type ChatMessagePaginator = {
  __typename?: 'ChatMessagePaginator';
  /** A list of ChatMessage items. */
  data: Array<ChatMessage>;
  /** Pagination information about the list of items. */
  paginatorInfo: PaginatorInfo;
};

export type ChatRoom = {
  __typename?: 'ChatRoom';
  id: Scalars['IntID']['output'];
  messages: Array<ChatMessage>;
  user: User;
};

export type Collection = WithUrls & {
  __typename?: 'Collection';
  breadcrumb?: Maybe<Scalars['String']['output']>;
  defaultUrl?: Maybe<Url>;
  id: Scalars['IntID']['output'];
  name?: Maybe<Scalars['String']['output']>;
  urls?: Maybe<Array<Maybe<Url>>>;
};


export type CollectionBreadcrumbArgs = {
  lang?: InputMaybe<Locales>;
};


export type CollectionNameArgs = {
  lang?: InputMaybe<Locales>;
};

export enum ConversionTypes {
  Large = 'LARGE',
  Medium = 'MEDIUM',
  Small = 'SMALL',
  Zoom = 'ZOOM'
}

export type Country = {
  __typename?: 'Country';
  capital: Scalars['String']['output'];
  currency: Scalars['String']['output'];
  emoji: Scalars['String']['output'];
  emojiU: Scalars['String']['output'];
  id: Scalars['IntID']['output'];
  iso2: Scalars['String']['output'];
  iso3: Scalars['String']['output'];
  name: Scalars['String']['output'];
  native: Scalars['String']['output'];
  phoneCode: Scalars['String']['output'];
};

export type CreateChatMessageInput = {
  body: Scalars['String']['input'];
  chatRoomId: Scalars['IntID']['input'];
};

export type CreateChatRoomResult = {
  __typename?: 'CreateChatRoomResult';
  query: Query;
  record: ChatRoom;
};

export type CreatePaymentIntentInput = {
  billingAddressId?: InputMaybe<Scalars['IntID']['input']>;
  shippingAddressId?: InputMaybe<Scalars['IntID']['input']>;
  shippingMethodId: Shipping_Methods;
};

export type CreateReviewInput = {
  body: Scalars['String']['input'];
  productVariantId: Scalars['IntID']['input'];
  rating: Scalars['Rating']['input'];
  title: Scalars['String']['input'];
};

export type Customer = {
  __typename?: 'Customer';
  firstName: Scalars['String']['output'];
  id: Scalars['IntID']['output'];
  lastName: Scalars['String']['output'];
};

export type DecreaseStockResult = {
  __typename?: 'DecreaseStockResult';
  query: Query;
  record: ProductVariant;
};

export enum Locales {
  English = 'ENGLISH',
  Spanish = 'SPANISH'
}

export type Media = {
  __typename?: 'Media';
  conversions?: Maybe<Array<Scalars['String']['output']>>;
  name: Scalars['String']['output'];
  original_url: Scalars['String']['output'];
};


export type MediaConversionsArgs = {
  types?: InputMaybe<Array<ConversionTypes>>;
};

/** A paginated list of Media items. */
export type MediaPaginator = {
  __typename?: 'MediaPaginator';
  /** A list of Media items. */
  data: Array<Media>;
  /** Pagination information about the list of items. */
  paginatorInfo: PaginatorInfo;
};

export type Mutation = {
  __typename?: 'Mutation';
  addItem: Cart;
  approveReview: Scalars['Boolean']['output'];
  archiveReview: Scalars['Boolean']['output'];
  capturePaymentIntent: Scalars['Boolean']['output'];
  clearCart: Cart;
  clearCartItem: Cart;
  createAddress: Address;
  createChatRoom: CreateChatRoomResult;
  createPaymentIntent: PaymentIntent;
  createReview: Review;
  decreaseStock: DecreaseStockResult;
  deleteAddress: Scalars['Boolean']['output'];
  deleteReview: Scalars['Boolean']['output'];
  /** Log in to a new session and get the user. */
  login: User;
  /** Log out from the current session, showing the user one last time. */
  logout?: Maybe<User>;
  removeItem: Cart;
  sendMessageToChatRoom: ChatMessage;
  /** Create a new store user. */
  signUp: User;
  updateAddress: Address;
};


export type MutationAddItemArgs = {
  productVariantId: Scalars['IntID']['input'];
  quantity?: InputMaybe<Scalars['Int']['input']>;
};


export type MutationApproveReviewArgs = {
  id: Scalars['IntID']['input'];
};


export type MutationArchiveReviewArgs = {
  id: Scalars['IntID']['input'];
};


export type MutationCapturePaymentIntentArgs = {
  input: CapturePaymentIntentInput;
};


export type MutationClearCartItemArgs = {
  productVariantId: Scalars['IntID']['input'];
};


export type MutationCreateAddressArgs = {
  input: AddressInput;
};


export type MutationCreatePaymentIntentArgs = {
  input: CreatePaymentIntentInput;
};


export type MutationCreateReviewArgs = {
  input: CreateReviewInput;
};


export type MutationDecreaseStockArgs = {
  id: Scalars['IntID']['input'];
};


export type MutationDeleteAddressArgs = {
  id: Scalars['IntID']['input'];
};


export type MutationDeleteReviewArgs = {
  id: Scalars['IntID']['input'];
};


export type MutationLoginArgs = {
  email: Scalars['String']['input'];
  password: Scalars['String']['input'];
};


export type MutationRemoveItemArgs = {
  productVariantId: Scalars['IntID']['input'];
  quantity?: InputMaybe<Scalars['Int']['input']>;
};


export type MutationSendMessageToChatRoomArgs = {
  input: CreateChatMessageInput;
};


export type MutationSignUpArgs = {
  input: SignUpInput;
};


export type MutationUpdateAddressArgs = {
  input: AddressInput;
};

export type OptionFilterInput = {
  optionTypeId: Scalars['IntID']['input'];
  valueIds: Array<Scalars['IntID']['input']>;
};

/** Allows ordering a list of records. */
export type OrderByClause = {
  /** The column that is used for ordering. */
  column: Scalars['String']['input'];
  /** The direction that is used for ordering. */
  order: SortOrder;
};

/** Aggregate functions when ordering by a relation without specifying a column. */
export enum OrderByRelationAggregateFunction {
  /** Amount of items. */
  Count = 'COUNT'
}

/** Aggregate functions when ordering by a relation that may specify a column. */
export enum OrderByRelationWithColumnAggregateFunction {
  /** Average. */
  Avg = 'AVG',
  /** Amount of items. */
  Count = 'COUNT',
  /** Maximum. */
  Max = 'MAX',
  /** Minimum. */
  Min = 'MIN',
  /** Sum. */
  Sum = 'SUM'
}

/** Information about pagination using a fully featured paginator. */
export type PaginatorInfo = {
  __typename?: 'PaginatorInfo';
  /** Number of items in the current page. */
  count: Scalars['Int']['output'];
  /** Index of the current page. */
  currentPage: Scalars['Int']['output'];
  /** Index of the first item in the current page. */
  firstItem?: Maybe<Scalars['Int']['output']>;
  /** Are there more pages after this one? */
  hasMorePages: Scalars['Boolean']['output'];
  /** Index of the last item in the current page. */
  lastItem?: Maybe<Scalars['Int']['output']>;
  /** Index of the last available page. */
  lastPage: Scalars['Int']['output'];
  /** Number of items per page. */
  perPage: Scalars['Int']['output'];
  /** Number of total available items. */
  total: Scalars['Int']['output'];
};

export type PaymentIntent = {
  __typename?: 'PaymentIntent';
  clientSecret: Scalars['String']['output'];
};

export type PriceFilterInput = {
  currency: Scalars['IntID']['input'];
  max?: InputMaybe<Scalars['Int']['input']>;
  min?: InputMaybe<Scalars['Int']['input']>;
};

export type Product = WithUrls & {
  __typename?: 'Product';
  brand: Brand;
  collections?: Maybe<Array<Maybe<Collection>>>;
  defaultUrl?: Maybe<Url>;
  description?: Maybe<Scalars['String']['output']>;
  id: Scalars['IntID']['output'];
  name?: Maybe<Scalars['String']['output']>;
  urls?: Maybe<Array<Maybe<Url>>>;
  variants?: Maybe<Array<ProductVariant>>;
};


export type ProductDescriptionArgs = {
  lang?: InputMaybe<Locales>;
};


export type ProductNameArgs = {
  lang?: InputMaybe<Locales>;
};

export type ProductVariant = WithDimensions & {
  __typename?: 'ProductVariant';
  description: Scalars['String']['output'];
  height?: Maybe<Scalars['Dimension']['output']>;
  id: Scalars['IntID']['output'];
  images: MediaPaginator;
  length?: Maybe<Scalars['Dimension']['output']>;
  name: Scalars['String']['output'];
  price: Scalars['Price']['output'];
  product: Product;
  stock: Scalars['Int']['output'];
  weight?: Maybe<Scalars['Dimension']['output']>;
  width?: Maybe<Scalars['Dimension']['output']>;
};


export type ProductVariantHeightArgs = {
  to?: InputMaybe<Units>;
};


export type ProductVariantImagesArgs = {
  first?: Scalars['Int']['input'];
  page?: InputMaybe<Scalars['Int']['input']>;
};


export type ProductVariantLengthArgs = {
  to?: InputMaybe<Units>;
};


export type ProductVariantPriceArgs = {
  currency_id?: InputMaybe<Scalars['IntID']['input']>;
};


export type ProductVariantWeightArgs = {
  to?: InputMaybe<Units>;
};


export type ProductVariantWidthArgs = {
  to?: InputMaybe<Units>;
};

export type ProductVariantFilter = {
  brand?: InputMaybe<Scalars['IntID']['input']>;
  collection?: InputMaybe<Scalars['IntID']['input']>;
  name?: InputMaybe<Scalars['String']['input']>;
  optionsFilter?: InputMaybe<Array<OptionFilterInput>>;
  priceFilter?: InputMaybe<PriceFilterInput>;
  type?: InputMaybe<Scalars['IntID']['input']>;
};

export enum ProductVariantOrderBy {
  NameAsc = 'NAME_ASC',
  NameDesc = 'NAME_DESC',
  PriceAsc = 'PRICE_ASC',
  PriceDesc = 'PRICE_DESC'
}

/** A paginated list of ProductVariant items. */
export type ProductVariantPaginator = {
  __typename?: 'ProductVariantPaginator';
  /** A list of ProductVariant items. */
  data: Array<ProductVariant>;
  /** Pagination information about the list of items. */
  paginatorInfo: PaginatorInfo;
};

export type Query = {
  __typename?: 'Query';
  chatRoomMessages: ChatMessagePaginator;
  findProducts: ProductVariantPaginator;
  /** Product details. */
  productById: Product;
  productBySlug?: Maybe<Product>;
  /** Available product root collections */
  rootCollections?: Maybe<Array<Collection>>;
  /** Find a single user by an identifying attribute. */
  user?: Maybe<User>;
  /** List multiple users. */
  users: UserPaginator;
};


export type QueryChatRoomMessagesArgs = {
  first?: Scalars['Int']['input'];
  page?: InputMaybe<Scalars['Int']['input']>;
};


export type QueryFindProductsArgs = {
  filter: ProductVariantFilter;
  first?: Scalars['Int']['input'];
  orderBy?: InputMaybe<ProductVariantOrderBy>;
  page?: InputMaybe<Scalars['Int']['input']>;
};


export type QueryProductByIdArgs = {
  id: Scalars['IntID']['input'];
};


export type QueryProductBySlugArgs = {
  slug: Scalars['String']['input'];
};


export type QueryUserArgs = {
  email?: InputMaybe<Scalars['String']['input']>;
  id?: InputMaybe<Scalars['IntID']['input']>;
};


export type QueryUsersArgs = {
  first?: Scalars['Int']['input'];
  name?: InputMaybe<Scalars['String']['input']>;
  page?: InputMaybe<Scalars['Int']['input']>;
};

export type Review = {
  __typename?: 'Review';
  body: Scalars['String']['output'];
  id: Scalars['IntID']['output'];
  productVariant: ProductVariant;
  rating: Scalars['Rating']['output'];
  title: Scalars['String']['output'];
  user: User;
};

export enum Shipping_Methods {
  Basdel = 'BASDEL',
  Expdel = 'EXPDEL',
  Pickup = 'PICKUP'
}

export type SignUpInput = {
  email: Scalars['String']['input'];
  firstName: Scalars['String']['input'];
  lastName: Scalars['String']['input'];
  password: Scalars['String']['input'];
};

/** Directions for ordering a list of records. */
export enum SortOrder {
  /** Sort records in ascending order. */
  Asc = 'ASC',
  /** Sort records in descending order. */
  Desc = 'DESC'
}

export type Subscription = {
  __typename?: 'Subscription';
  stockDecreased?: Maybe<ProductVariant>;
  updateChatRoom?: Maybe<ChatMessage>;
};


export type SubscriptionStockDecreasedArgs = {
  id: Scalars['IntID']['input'];
};


export type SubscriptionUpdateChatRoomArgs = {
  chatRoomId: Scalars['IntID']['input'];
};

/** Specify if you want to include or exclude trashed results from a query. */
export enum Trashed {
  /** Only return trashed results. */
  Only = 'ONLY',
  /** Return both trashed and non-trashed results. */
  With = 'WITH',
  /** Only return non-trashed results. */
  Without = 'WITHOUT'
}

export enum Units {
  Centimeters = 'CENTIMETERS',
  Kilogram = 'KILOGRAM'
}

export type Url = {
  __typename?: 'Url';
  language_id: Scalars['IntID']['output'];
  slug: Scalars['String']['output'];
};

/** Account of a person who utilizes this application. */
export type User = {
  __typename?: 'User';
  /** When the account was created. */
  created_at: Scalars['DateTime']['output'];
  /** Unique email address. */
  email: Scalars['String']['output'];
  /** When the email was verified. */
  email_verified_at?: Maybe<Scalars['DateTime']['output']>;
  /** Unique primary key. */
  id: Scalars['IntID']['output'];
  /** Non-unique name. */
  name: Scalars['String']['output'];
  /** When the account was last updated. */
  updated_at: Scalars['DateTime']['output'];
};

/** A paginated list of User items. */
export type UserPaginator = {
  __typename?: 'UserPaginator';
  /** A list of User items. */
  data: Array<User>;
  /** Pagination information about the list of items. */
  paginatorInfo: PaginatorInfo;
};

export type WithDimensions = {
  height?: Maybe<Scalars['Dimension']['output']>;
  length?: Maybe<Scalars['Dimension']['output']>;
  weight?: Maybe<Scalars['Dimension']['output']>;
  width?: Maybe<Scalars['Dimension']['output']>;
};


export type WithDimensionsHeightArgs = {
  to?: InputMaybe<Units>;
};


export type WithDimensionsLengthArgs = {
  to?: InputMaybe<Units>;
};


export type WithDimensionsWeightArgs = {
  to?: InputMaybe<Units>;
};


export type WithDimensionsWidthArgs = {
  to?: InputMaybe<Units>;
};

export type WithUrls = {
  defaultUrl?: Maybe<Url>;
  urls?: Maybe<Array<Maybe<Url>>>;
};

export type RootCollectionsQueryVariables = Exact<{ [key: string]: never; }>;


export type RootCollectionsQuery = { __typename?: 'Query', rootCollections?: Array<{ __typename?: 'Collection', id: any }> | null };

export type GetRootCollectionsQueryVariables = Exact<{ [key: string]: never; }>;


export type GetRootCollectionsQuery = { __typename?: 'Query', rootCollections?: Array<{ __typename?: 'Collection', id: any, name?: string | null }> | null };


export const RootCollectionsDocument = {"kind":"Document","definitions":[{"kind":"OperationDefinition","operation":"query","name":{"kind":"Name","value":"rootCollections"},"selectionSet":{"kind":"SelectionSet","selections":[{"kind":"Field","name":{"kind":"Name","value":"rootCollections"},"selectionSet":{"kind":"SelectionSet","selections":[{"kind":"Field","name":{"kind":"Name","value":"id"}}]}}]}}]} as unknown as DocumentNode<RootCollectionsQuery, RootCollectionsQueryVariables>;
export const GetRootCollectionsDocument = {"kind":"Document","definitions":[{"kind":"OperationDefinition","operation":"query","name":{"kind":"Name","value":"GetRootCollections"},"selectionSet":{"kind":"SelectionSet","selections":[{"kind":"Field","name":{"kind":"Name","value":"rootCollections"},"selectionSet":{"kind":"SelectionSet","selections":[{"kind":"Field","name":{"kind":"Name","value":"id"}},{"kind":"Field","name":{"kind":"Name","value":"name"}}]}}]}}]} as unknown as DocumentNode<GetRootCollectionsQuery, GetRootCollectionsQueryVariables>;